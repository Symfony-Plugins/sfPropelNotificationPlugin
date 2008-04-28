<?php

class sfTestLogicProvider
{

  public static function NewUser(BaseObject $object)
  {
    return $object->wasNew();
  }

  public static function OtherTestType(BaseObject $object)
  {
    return !$object->wasNew();
  }


}
?>