# dcp-di-php
Automatically exported from code.google.com/p/dcp-di-php
This container is used for resolve dependencies

Example:

$con = new DIContainer();

$d = new DependencyItem(); $d->Name = "UserRepository"; $d->Type = "object"; $d->Value = "DAL\UserRepository"; //for objects uses fullname type $d->Dependencies = array("connectionString", "user", "password"); //list names of other "DependencyItem" items $con->RegistrateDependency($d);

$d = new DependencyItem(); $d->Name = "connectionString"; $d->Type = "string"; $d->Value = DBCHAT_CONNECTION_STRING; $con->RegistrateDependency($d);

$d = new DependencyItem(); $d->Name = "user"; $d->Type = "string"; $d->Value = "root"; $con->RegistrateDependency($d);

$d = new DependencyItem(); $d->Name = "password"; $d->Type = "string"; $d->Value = ""; $con->RegistrateDependency($d);

$repository = $con->ResolveType("UserRepository");
