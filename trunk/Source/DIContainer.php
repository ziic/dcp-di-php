<?php

namespace DI;

class DependencyItem
{
    public $Name;
    public $Type;
    public $Value;

    public $Dependencies;
}

class DIContainer
{
    private $_dependencies = array();
	private $_alreadyResolvedDependecies = array();

    public function RegistrateDependency(DependencyItem $d)
    {
        $this->_dependencies[] = $d;
    }

    public function ResolveType($type)
    {
		if (array_key_exists($type, $this->_alreadyResolvedDependecies))
			return $this->_alreadyResolvedDependecies[$type];

		$dependency = $this->GetDependencyForType($type);

        $instance = $this->ResolveDependency($dependency);

		if (!array_key_exists($type, $this->_alreadyResolvedDependecies))
		{
			$this->_alreadyResolvedDependecies[$type] = $instance;
		}

        return $instance;
    }

    private function GetDependencyForType($type)
    {
        foreach ($this->_dependencies as $dependency)
        {
            if ($dependency->Name == $type)
                return $dependency;
        }
        return null;
    }

    private function ResolveDependency(DependencyItem $dep)
    {
        $resolvedDependecies = array();

		if (is_array($dep->Dependencies))
			foreach ($dep->Dependencies as $type)
			{
			$dependecy = $this->GetDependencyForType($type);
			$resolveRes = $this->ResolveDependency($dependecy);
			$resolvedDependecies[] = $resolveRes;
			}

		$instance = $this->CreateDependency($dep,$resolvedDependecies);

        return $instance;

    }


    private function CreateDependency(DependencyItem $dep, $arrayResolved)
    {
        if ($dep->Type == "object")
        {
            $code = "\$res = new ".$dep->Value."(";
	    $i=0;
            foreach ($arrayResolved as $value)
            {
                if (is_string($value))
		    $code .= $value = "\"".$value."\", ";
		else if (is_object($value))
		{
		    $code .= "\$arrayResolved[".$i."], ";
		}
		else
		    $code .= $value.", ";

		$i++;
            }
            $code = trim($code, ", ");
            $code = $code.")";
	    $code .= ";";
	    eval($code);
        }
        else if ($dep->Type == "interface")
	{
	    $resolved = $arrayResolved[0];
	    $res = $resolved;
	}
	else
        {
            $code = "\$res = (".$dep->Type.")";
			if ($dep->Type == "string")
				$code .= "\"".$dep->Value."\"";
			else
				$code = $dep->Value;
	    $code .= ";";
	    eval($code);
        }
        return $res;
    }

    private function CreateObject($dep)
    {
	$code = "\$res = new ".$dep->Value."(";
            foreach ($arrayResolved as $value)
            {
                if (is_string($value))
		    $value = "\"".$value."\"";
		$code .= $value.", ";
            }
            $code = trim($code, ", ");
            $code = $code.")";
	    	$code .= ";";
        eval($code);
        return $res;
    }

    private function CreateInterface($dep)
    {

    }

    private function CreateValue($dep)
    {
	$code = "\$res = (".$dep->Type.")";
	if ($dep->Type == "string")
		$code .= "\"".$dep->Value."\"";
	else
		$code = $dep->Value;
	$code .= ";";
        eval($code);
	return $res;
    }
}

?>