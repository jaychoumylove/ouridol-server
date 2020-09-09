<?php


namespace app\base\service;


use app\base\service\alipay\AopClient;

class AliPayApi
{
    public static function getInstance()
    {
        $aop = new AopClient ();

        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2021001193677231';
        $aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEA3651dvpmJ9LECTILGA23bUB8c94SzbhVEV8H0tCQY3qx6vdV9aNqgsF98i8kojP6ls+cRLIXMKbRft/ziZEGssOtkyWTJ+7MFISQAUwlKq1xRblLbc/PZ9Tjjez3eh10nm6pv5/YGNJA/IP1Bp26Pnqd633N0C2GJmbxzN041/1Ud8ml2qZHH7idXs+l4gGmV+QYvnRe3BU8xNv2coUEAcAWXwgf6aAMxwN527ucpJuNkJheozySFw+i+aLXsSHKAa+DmgF9OSZ3jgUazeXUykgpEni5fdq+FlEqRhklguT1ZOpp9KXhudjj3hpV68AynS//bO9wffvzfX5puRm1twIDAQABAoIBAQCKUwixGwgxeFViml/h8BmOpmJQBPT74xI4O9xKsk+YNTfxZiLyVKx5T87jBeRRqnM88AZjNP7CR2cmXrfsSpMp2Xhtlr754uKxjYjARKZ3TerOg1fyAKDyROSzz+b6zVxg3W574g1wzBUEE1ZjUQbrvEgMaS/tnTloZiEB34xXhFm1LKsM+sMDB7TM9qyniOi4DbKDEHeX0/H1ssDO92/apak1K/d0P9zA9BH79h+7rzVcvq8JO0LGLK4DbOwsj9HIEq6/EJoN6B8b6LwAUWcXr0f/TCXPL6IA5p1TWxYT7iwkHsxQyWU639TDrd+BiAyVFHn4ubpO5sA+OaGDiHaBAoGBAPXdVYZx20Yw1WxMxJHFSpfePwBR92yqqixbwC6ghlOKQmute+4jpbkT3EPSFSyMt3k3ox7bmR1wEIk3QTJd6nVdrNXkay1wwE8nj/Vx/3XtLaQKGqgzWqoY5AHGSqXSYFm+RfA84K7TQci4hv/jM1ymbHmXu1QyHHi4mEcJZ9V1AoGBAOjnBXQ+aN5h7xFoR/XnnsyS+M/0ZyZKaOFAwOGUzEPOxupL8ZW555+YXZDlUPaZxHBxsY/GKJWxG0WFKt7fa325gmH/+c3SSi5wUAE4pO8g4OoBq8FGufs5tVSaIbevrsCGiRV2i/UfW3q7pEuGu9fm/aRFduVc8kiCCDJEtDz7AoGANNcl22g67SHvrH/vOzkAqBUOyz3ShFtFiZUKf7rmQ6wSTFwTp4Gny1gWXckX3eQ8RmQLvcUKakY8d5EVhQKkMic4zxtxYWm7Gwfwq5qdgbXAumGliwLGeIoy7qK0RmEpbgAzvccHruseV0o5UWN+FWp7I8LVcun2LofVymaeF0UCgYEAvzTK+YAzQD9Osg/W5D9f07vloXSZKop0SFmNAKXUfObJz5jBoZaaXiAM0OQy8AGVkGsGx25760kXhp+pbogmC3W9YQ9x2lQGqtFyHU6PufJYNEVi6K5UCbh68hYIEHGfGlhZOtG0XW19AOoEHC1lEf+FxNNypSMFlhmK+RoGjvkCgYAMuuwHYHQvNV+MCMfZHhaE7a1gkiG9KJ005TATkLKrJf/FzrYKxjNvgljZ9WXoG6wFBrtsLcKpLH6kjhTutmjYr6Xtyd3oWBBuXAJQpdluCoAXrhN9z6sTKowKZlolxt7RswX1fxh2FC+puC3WcHmKQRej0dWZfhi34p4SYRRBFA==';
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhrEbj68YX/SCcYhS4GGw6sJtxyZlDx584Aw27x3SwBGVMjkid1nd9eJP8nTtLWXZLUU8lqNXdLPHZIuPgBpjZla10P8t92zgn2WRDhg+1AwDAEsSHw4b0Fqw5IQoxMJnfewJlZHGmiMvDqWdTb9LMemlZZBobK2/tfmxSDmA6rHN+9bwa8ABDI+8sFRlISb+sOebqOlRQ0QLFStanxAum+iYZeZno1TrNzdkld1kHvHGLqwL3SO6uDWzxrJkVa+qQfy+/OjLL7Xj8ahB+P59KSndE7jRNSDEMJhzeYhRYGiVTm3kxwT+KsQsN6WEMOx/ETj0OZ8bM4i9Y/bhYQRhmQIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';

        return $aop;
    }
}