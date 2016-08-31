<?php

$app->get('/tasks', 'controller.task:showAction')->bind('tasks');
$app->post('/tasks', 'controller.task:newAction')->bind('new_task');
$app->delete('/tasks', 'controller.task:deleteAction')->bind('delete_task');
$app->put('/tasks', 'controller.task:updateAction')->bind('update_task');

