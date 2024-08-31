<?php

class uninstall extends humhub\components\Migration
{

    public function up()
    {
        $this->dropTable('reported_content');
    }

    public function down()
    {
        echo "m240831_055420_initial does not support migration down.\n";
        return false;
    }

}

?>
