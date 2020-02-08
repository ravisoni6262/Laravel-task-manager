@if(count($items))
    <ul class="project-list">
        @foreach($items as $item)
            <li data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                @if($item->selected)
                    <span class="project-selected">SELECTED</span>
                @endif
                <span class="project-name">{{ $item->name }}</span>
                <div class="actions">
                    <button class="btn btn-primary btn-sm btn-edit">Edit</button>
                    <button class="btn btn-danger btn-sm btn-delete">Delete</button>
                </div>
            </li>
        @endforeach
    </ul>
    <script>
        $('.project-list li').each(function () {
            let item = $(this)
            let id = item.attr('data-id')
            item.find('.btn-delete').click(function (e) {
                e.preventDefault()
                $(this).attr('disabled', 'disabled')
                $(this).prepend(`<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>`)
                deleteProject(id)
            })

            item.find('.btn-edit').click(function (e) {
                e.preventDefault()
                let newModal = $('#projectFormModal')
                newModal.find('form input[name=type]').val('edit')
                newModal.find('form input[name=name]').val(item.attr('data-name'))
                newModal.find('form input[name=id]').val(item.attr('data-id'))
                newModal.modal('show')
            })

            item.find('.project-name').click(function (e) {
                selectProject(id)
            })

        })
    </script>
@else
    <div class="container">
        <h5>No data!</h5>
    </div>
@endif
