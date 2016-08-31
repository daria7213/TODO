$(function(){
    var Task = {

        init: function(){
            this.bindActions();
        },
        bindActions: function(){
            var taskList = $('.task-list');

            $('.task-add').click(this.addTask);

            taskList.on('click', '.task-delete', function(){
                Task.deleteTask($(this).closest('.task-item'));
            });
            taskList.on('change', '.task-status:not(.adding)', function () {
                Task.changeTaskList($(this).closest('.task-item'));
                Task.updateTask($(this).closest('.task-item'));
            });
            taskList.on('change', '.task-description:not(.adding)', function(){
                Task.updateTask($(this).closest('.task-item'));
            });

        },
        addTask: function(){
            $.ajax({
                type: 'POST',
                url: '/',
                dataType: 'json',
                data: {
                    description: $('.new-task .task-description').val(),
                    status: false
                },
                success: function(taskData,status){
                    Task.appendTask($.parseJSON(taskData));
                }
            });
        },
        deleteTask: function(task) {
            $.ajax({
                url: '/',
                type:'DELETE',
                dataType: 'text',
                data: {
                    id: task.attr('id')
                },
                success: function(text, status){
                    task.remove()
                }
            });
        },

        updateTask: function(task){
            $.ajax({
                type: 'PUT',
                url: '/',
                data: {
                    id: task.attr('id'),
                    status: task.find('.task-status').prop('checked'),
                    description: task.find('.task-description').val()
                },
                success: function(text, status){
                    //alert(text);
                }
            });
        },
        appendTask: function(taskData){
            var undoneTaskList = $('.undone-tasks');

            var newTask = '<div class="task-item" id='+taskData.id+'>'+
                            '<div class="input-group">'+
                            '<span class="input-group-addon">'+
                            '<label class="task-checkbox">'+
                            '<input class="task-status" type="checkbox" title = "Status" aria-label="Status"' + (taskData.status === true ? "checked": "") + '>'+
                            '<span class="indicator fa fa-check" role="img" aria-label="Important"></span></label></span>'+
                            '<input type="text" class="task-description form-control" title="Task" aria-label="Task" value="'+ taskData.description +'">'+
                            '<div class="input-group-btn">'+
                            '<button class="task-delete btn " type="button">'+
                            '<span class="fa fa-times" role="img" aria-label="Delete"></span></button></div></div></div>';

            undoneTaskList.append(newTask);
            undoneTaskList.find('#'+taskData.id).find('.task-status, .task-description').addClass('adding');
            undoneTaskList.find('#'+taskData.id).find('.task-status, .task-description').removeClass('adding');
        },

        changeTaskList: function(task){
            if(task.find('.task-status').prop('checked')){
                task.detach();
                task.appendTo(".done-tasks");
            }else {
                task.detach();
                task.appendTo(".undone-tasks");
            }
        }
    };

    Task.init();
});