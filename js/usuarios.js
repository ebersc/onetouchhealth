$(document).ready(function () {
    $('#tabelaUsuarios').DataTable({
        searching: false,
        paging: false,
        processing: true,
        serverSide: false,
        ordering: true,
        ajax: {
            "url": "usuarios.class.php",
            "data": { "action": 'listar' },
            "type": "POST"
        },
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
        }
    });
});

function excluirUsuario(id) {
    Swal.fire({
        title: "Deseja realmente excluir esse usuário?",
        icon: "question",
        showDenyButton: true,
        confirmButtonText: "Sim",
        denyButtonText: "Não"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "usuarios.class.php",
                type: "POST",
                data: {
                    "action": "deletar",
                    "id": id
                },
                success: function (resp) {
                    resp = JSON.parse(resp);
                    if (resp.status == 200) {
                        Swal.fire({
                            title: resp.msg,
                            text: "",
                            icon: "success"
                        });
                        atualizarTabela();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro na requisição:", status, error);
                }
            });
        } else {
            Swal.fire("Operação cancelada com sucesso", "", "error");
        }
    });
}

$("#btnAdicionar").on("click", function () {
    $("#modalUsuario").modal("show");
});

$("#btnSalvar").on("click", function () {
    let nome = $("#nome").val();
    let email = $("#email").val();
    $.ajax({
        url: "usuarios.class.php",
        type: "POST",
        data: {
            "action": "salvar",
            "nome": nome,
            "email": email
        },
        success: function (resp) {
            resp = JSON.parse(resp);
            if (resp.status == 200) {
                Swal.fire({
                    title: resp.msg,
                    text: "",
                    icon: "success"
                });
                $("#nome").val('');
                $("#email").val('');
                atualizarTabela();
            }
        },
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", status, error);
        }
    });
});

function atualizarTabela() {
    $("#modalUsuario").modal('hide');
    $('#tabelaUsuarios').DataTable().ajax.reload();
}