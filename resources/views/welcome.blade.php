<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 45px;
            height: 25px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 2px;
            bottom: 5px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .listItmes {
            display: inline-block;
        }

        ul {
            display: flex;
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        li {
            flex: 1;
        }

        .same {
            max-width: 100%;
            display: inline-block;
            margin-right: 3em;
            box-sizing: border-box;
        }
    </style>

</head>

<body>
    <div class="container w-50 mt-4 mb-4">
        <div class="card">
            <div class="card-header">
                <label class="switch">
                    <input type="checkbox" id="showAllTask">
                    <span class="slider"></span>
                </label>&nbsp;&nbsp;&nbsp;<label for="showAllTask">
                    <p> Show All</p>
                </label>

                <div class="input-group mb-1 mt-3">
                    <input type="text" class="form-control" placeholder="Enter Task" id="task">
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="addTask" type="button">Add</button>
                    </div>
                </div>
                <div id="msg"></div>
            </div>
            <div class="card-body mt-4 mb-4">
                <div class="table-responsive text-center">
                    <table class="table table-bordered table-hover" id="taskTbl" style="width: 100%">
                        {{-- <tr>
                            <td><input type="checkbox" id="check1"></td>
                            <td><label for="check1">Task Listfgdfgsdfgdsfgdsfgfdgsdfgdsfgdfgv
                                    fdklhdsfkhsdfhudf</label></td>
                            <td><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                        </tr> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function dataNotFound(){
            setTimeout(() => {
                if ($("#taskTbl tr").length > 0) {
                    $('.nodata').hide();
                } else {
                    $('#taskTbl').html('<h3 class="text-center nodata">No Task Available.</h3>')
                }
            }, 500);
        }

        function showAll() {
            $.ajax({
                type: "get",
                url: "{{ route('getAllList') }}",
                success: function(response) {
                    // console.log(response)
                    $.map(response.task, function(val, i) {
                        let checkcomplete = val.is_complete == 1 ? 'checked' : '';
                        let html = `<tr>
                            <td><input type="checkbox" id="check${val.id}" ${checkcomplete} class="complete" data-id="${val.id}"></td>
                            <td><label for="check${val.id}" data-id="${val.id}">${val.task}</label></td>
                            <td><button class="btn btn-danger btn-sm delete" data-id="${val.id}"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
                        $('#taskTbl').append(html)
                    });
                    dataNotFound()
                }
            });
        }

        function showNotCompleteTask() {
            $.ajax({
                type: "get",
                url: "{{ route('getNotCompleteTask') }}",
                success: function(response) {
                    // console.log(response)
                    $.map(response.task, function(val, i) {
                        let html = `<tr>
                            <td><input type="checkbox" id="check${val.id}" class="complete" data-id="${val.id}"></td>
                            <td><label for="check${val.id}" data-id="${val.id}">${val.task}</label></td>
                            <td><button class="btn btn-danger btn-sm delete" data-id="${val.id}"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
                        $('#taskTbl').append(html)
                    });
                    dataNotFound()
                }
            });
        }
        showNotCompleteTask()
        // Show All Task
        $(document).on('click', '#showAllTask', function() {
            $("#taskTbl").empty();
            if ($(this).prop("checked") == true) {
                showAll()
            } else {
                showNotCompleteTask()
            }
        })
        // Show All Task End

        // Done Task Remove
        $(document).on('click', '.complete', function() {
            // doneTask
            let click = $(this)
            let id = $(this).data('id')
            if ($(this).prop("checked") == false) {
                $.ajax({
                    type: "post",
                    url: "{{ route('removeDoneTask') }}",
                    data: {
                        'id': id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        console.log(result)
                        click.prop("checked", false)
                        Swal.fire(
                                    'Mark Not Complete!',
                                    'Your task not complete.',
                                    'success'
                                )
                    }
                });
            } else if ($(this).prop("checked") == true) {
                $.ajax({
                    type: "post",
                    url: "{{ route('doneTask') }}",
                    data: {
                        'id': id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if($('#showAllTask').prop("checked") == false){
                            click.closest("tr").fadeOut(1000).delay(5000).remove()
                        }
                        Swal.fire(
                                    'Mark Completed!',
                                    'Your task completed.',
                                    'success'
                                )
                        dataNotFound()
                    }
                });
            }

        })
        // Done Task Remove End

        // Check Exiest or Not
        $(document).ready(function() {
            // $('#addTask').attr("disabled", true)
            $('#task').on('keyup', function() {
                var task = $(this).val();
                if (task != '') {
                    $.ajax({
                        type: "post",
                        url: "{{ route('checkTask') }}",
                        data: {
                            "task": task,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(result) {
                            // console.log(result)
                            if (result.result === "exist") {
                                $('#msg').html(
                                    `<span class="text-danger">Task already exiest. Not able to add.</span>`
                                );
                                $('#addTask').attr("disabled", true)
                            } else {
                                $('#msg').html('');
                                $('#addTask').attr("disabled", false)
                            }
                        }
                    });
                } else {
                    $('#msg').html('');
                    $('#addTask').attr("disabled", false)
                }
            });
        });
        // Check Exiest Or Not End

        // Add Task
        $('#addTask').on('click', function() {
            let taskval = $('#task').val();
            if ($('#addTask').attr("disabled", false)) {
                $.ajax({
                    type: "post",
                    url: "{{ route('addTask') }}",
                    data: {
                        "task": taskval,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {

                        let html = `<tr>
                            <td><input type="checkbox" id="check${response.task.id}" class="complete" data-id="${response.task.id}"></td>
                            <td><label for="check${response.task.id}" data-id="${response.task.id}">${response.task.task}</label></td>
                            <td><button class="btn btn-danger btn-sm delete" data-id="${response.task.id}"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
                        $('#taskTbl').prepend(html)
                        $('#task').val('');
                        dataNotFound()
                    }
                });
            }
        })
        // Add Task End


        // Delete Task
        $(document).on('click', '.delete', function() {
            let clickBtn = $(this)
            var postID = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this task!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('deleteTask') }}",
                        data: {
                            "id": postID,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.result === 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Your task has been deleted.',
                                    'success'
                                )
                                clickBtn.closest("tr").fadeOut(1000).delay(5000).remove()
                                dataNotFound()
                            }
                        }
                    });

                }
            })

        });
        // Delete Task End
    </script>
</body>

</html>
