<?php

$app->get('/', 'controller.task:showAction')->bind('tasks');
$app->post('/', 'controller.task:newAction')->bind('new_task');
$app->delete('/', 'controller.task:deleteAction')->bind('delete_task');
$app->put('/', 'controller.task:updateAction')->bind('update_task');

