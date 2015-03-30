<?php
  $installer = $this;
  $installer->startSetup();
  $installer->run(" 
    ALTER TABLE {$this->getTable('sales_flat_order')} ADD `ccare_username` VARCHAR(255) NULL, ADD `user_operatingsystem` VARCHAR(255) NULL , ADD `user_browser` VARCHAR(255) NULL;
  ");
  $installer->endSetup();
  ?>