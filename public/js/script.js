const url = $('meta[name=base-url]').attr('content')
const navProjects = $('.project-list')
const modalProjects = $('#projectsModal')
const listProject = $('#project-list')
const listTasks = $('.list-tasks')
const btnReload = $('.btn-reload')
const modalTaskForm = $('#taskFormModal')

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

btnReload.click(function (e) {
    getTasks()
})

navProjects.click(function (e) {
    e.preventDefault()
    getProjects()
})

modalProjects.find('.btn-new').click(function (e) {
    e.preventDefault()
    let newModal = $('#projectFormModal')
    newModal.find('form')[0].reset()
    newModal.find('form input[name=type]').val('new')
    newModal.modal('show')
})

modalTaskForm.find('.btn-save').click(function (e) {
    e.preventDefault()
    saveTask($(this))
})

$('#projectFormModal .btn-save').click(function (e) {
    e.preventDefault()
    saveProject($(this))
})

$('form input').keypress(function (e) {
    if (e.which == 13) {
        return false;
    }
});

getTasks()

function getProjects() {
    let loading = listProject.find('.loading')
    let content = listProject.find('.content')

    loading.fadeIn(200)

    $.ajax({
        url: `${url}/projects`,
        success: function (response) {
            content.html(response)
        },
        fail: function (xhr, textStatus, errorThrown) {

        },
        complete: function (data) {
            loading.fadeOut(200)
        }
    })
}

function deleteProject(project_id) {
    $.ajax({
        method: 'post',
        url: `${url}/projects/delete`,
        data: {
            'id': project_id
        },
        success: function (response) {
            if (response.result == 1) {
                Snackbar.show({
                    text: response.message,
                    pos: 'bottom-center',
                    actionText: 'Restore',
                    duration: 7000,
                    onActionClick: function (element) {
                        $(element).css('opacity', 0);
                        restoreProject(project_id)
                    }
                });
            }
        },
        fail: function (xhr, textStatus, errorThrown) {

        },
        complete: function (data) {
            getProjects()
        }
    })
}

function saveProject(button = null) {
    let formModal = $('#projectFormModal')
    let form = formModal.find('form')

    button.attr('disabled', 'disabled')
    button.prepend(`<span class="loader spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>`)

    $.ajax({
        method: 'post',
        url: `${url}/projects/save`,
        data: form.serialize(),
        success: function (response) {
            getProjects()
            formModal.modal('hide')
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors
                Object.keys(errors).map(key => {
                    Snackbar.show({
                        text: errors[key],
                        pos: 'bottom-center',
                        actionText: 'OK',
                    });
                })
            } else {
                Snackbar.show({
                    text: 'Server Error!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                });
            }
        },
        complete: function (data) {
            button.removeAttr('disabled')
            button.find('.loader').remove()
        }
    })
}

function restoreProject(project_id) {
    $.ajax({
        method: 'post',
        url: `${url}/projects/restore`,
        data: {
            id: project_id
        },
        success: function (response) {
            if (response.result == 1) {
                // Snackbar.show({
                //     text: response.message,
                //     pos: 'bottom-center',
                //     actionText: 'OK',
                // });
                getProjects()
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors
                Object.keys(errors).map(key => {
                    Snackbar.show({
                        text: errors[key],
                        pos: 'bottom-center',
                        actionText: 'OK',
                    });
                })
            } else {
                Snackbar.show({
                    text: 'Server Error!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                });
            }
        },
        complete: function (data) {
        }
    })
}

function selectProject(project_id) {
    $.ajax({
        method: 'post',
        url: `${url}/projects/select`,
        data: {
            id: project_id
        },
        success: function (response) {
            if (response.result == 1) {
                getProjects()
                $('.selected-project').html(response.name)
                $('.selected-project').attr('data-id', response.id)
                getTasks()
                modalProjects.modal('hide')
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors
                Object.keys(errors).map(key => {
                    Snackbar.show({
                        text: errors[key],
                        pos: 'bottom-center',
                        actionText: 'OK',
                    });
                })
            } else {
                Snackbar.show({
                    text: 'Server Error!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                });
            }
        },
        complete: function (data) {
        }

    })
}

//Tasks
function getTasks(id = null) {
    let loading = listTasks.find('.loading')
    let content = listTasks.find('.tasks-content')

    if (id == null) {
        let project_id = $('.selected-project').attr('data-id')
        if (project_id == '' || project_id == null || project_id == 'null' || project_id == undefined) {

            loading.fadeOut(200)
            content.html('Please select a project')

            return false
        }
        id = project_id
    }


    loading.fadeIn(200)

    $.ajax({
        url: `${url}/tasks`,
        data: {
            project_id: id
        },
        success: function (response) {
            content.html(response)
        },
        fail: function (xhr, textStatus, errorThrown) {

        },
        complete: function (data) {
            loading.fadeOut(200)
        },
        statusCode: {
            500: function () {
                Snackbar.show({
                    text: 'Server Error 500 - Reload Page!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                    duration: 100000000
                });
            }
        }
    })
}

function taskSort(task_id, sort, data = null) {
    $.ajax({
        method: 'post',
        url: `${url}/tasks/sort`,
        data: {
            items: data
        },
        success: function (response) {
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors
                Object.keys(errors).map(key => {
                    Snackbar.show({
                        text: errors[key],
                        pos: 'bottom-center',
                        actionText: 'OK',
                    });
                })
            } else {
                Snackbar.show({
                    text: 'Server Error!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                });
            }
        },
        complete: function (data) {
        }

    })
}

function saveTask(button = null) {
    let form = modalTaskForm.find('form')

    button.attr('disabled', 'disabled')
    button.prepend(`<span class="loader spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>`)

    $.ajax({
        method: 'post',
        url: `${url}/tasks/save`,
        data: form.serialize(),
        success: function (response) {
            getTasks()
            modalTaskForm.modal('hide')
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors
                Object.keys(errors).map(key => {
                    Snackbar.show({
                        text: errors[key],
                        pos: 'bottom-center',
                        actionText: 'OK',
                    });
                })
            } else {
                Snackbar.show({
                    text: 'Server Error!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                });
            }
        },
        complete: function (data) {
            button.removeAttr('disabled')
            button.find('.loader').remove()
        }
    })
}

function deleteTask(task_id) {
    $.ajax({
        method: 'post',
        url: `${url}/tasks/delete`,
        data: {
            'id': task_id
        },
        success: function (response) {
            if (response.result == 1) {
                Snackbar.show({
                    text: response.message,
                    pos: 'bottom-center',
                    actionText: 'Restore',
                    duration: 7000,
                    onActionClick: function (element) {
                        $(element).css('opacity', 0);
                        restoreTask(task_id)
                    }
                });
            }
        },
        fail: function (xhr, textStatus, errorThrown) {

        },
        complete: function (data) {
            getTasks()
        }
    })
}

function restoreTask(task_id) {
    $.ajax({
        method: 'post',
        url: `${url}/tasks/restore`,
        data: {
            id: task_id
        },
        success: function (response) {
            if (response.result == 1) {
                // Snackbar.show({
                //     text: response.message,
                //     pos: 'bottom-center',
                //     actionText: 'OK',
                // });
                getTasks()
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors
                Object.keys(errors).map(key => {
                    Snackbar.show({
                        text: errors[key],
                        pos: 'bottom-center',
                        actionText: 'OK',
                    });
                })
            } else {
                Snackbar.show({
                    text: 'Server Error!',
                    pos: 'bottom-center',
                    actionText: 'OK',
                });
            }
        },
        complete: function (data) {
        }
    })
}
