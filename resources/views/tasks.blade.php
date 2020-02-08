@if(count($tasks))
    <ul class="tasks-ul">
        @foreach($tasks as $key=>$task)
            <li id="task-{{ $task->id }}" data-id="{{ $task->id }}" data-name="{{ $task->name }}">
                <span>{{ $task->name }}</span>
                <div class="actions">
                    <button class="btn btn-primary btn-sm btn-edit">Edit</button>
                    <button class="btn btn-danger btn-sm btn-delete">Delete</button>
                </div>
            </li>
        @endforeach
    </ul>
    <script>
        $(".tasks-ul").sortable({
            axis: "y",
            cursor: "move",
            update: function (event, ui) {
                let index = ui.item.index()
                index++
                let id = ui.item.attr('data-id')
                taskSort(id, index, $(this).sortable('serialize'))
            }
        });
        $(".tasks-ul").disableSelection();

        $('.tasks-ul li').each(function () {
            let item = $(this)
            let id = item.attr('data-id')
            item.find('.btn-delete').click(function (e) {
                e.preventDefault()
                $(this).attr('disabled', 'disabled')
                $(this).prepend(`<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>`)
                deleteTask(id)
            })

            item.find('.btn-edit').click(function (e) {
                e.preventDefault()
                let newModal = $('#taskFormModal')
                newModal.find('form input[name=type]').val('edit')
                newModal.find('form input[name=name]').val(item.attr('data-name'))
                newModal.find('form input[name=id]').val(item.attr('data-id'))
                newModal.find('form input[name=project_id]').val($('.selected-project').attr('data-id'))
                newModal.modal('show')
            })
        })
    </script>
@endif
<div class="container py-5">
    <button class="btn btn-block btn-outline-success btn-add-task" type="button">Add Task</button>
</div>
<script>
    $('.btn-add-task').click(function (e) {
        e.preventDefault()
        modalTaskForm.modal('show')
        let form = modalTaskForm.find('form')
        form[0].reset()
        form.find('input[name=type]').val('new')
        form.find('input[name=project_id]').val($('.selected-project').attr('data-id'))
    })
</script>
