
const fetchGet = () => {
    //ajax para servidor php
    fetch('http://127.0.0.1:8000/api/clientes', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        alimentarTabela(data)
    })
    .catch(error => {
        console.error(error);
    });
    }

function alimentarTabela(data){
    const table = document.getElementById('tbody-tabela-clientes')
    const tableMae = document.getElementById('tabela-clientes')

    if(data.length == 0){
        tableMae.innerHTML = "NENHUM USUÁRIO ENCONTRADO"
    }else{
        data.forEach(cliente => {
            if(cliente.id <= 10){
                //console.log(cliente) //teste
                const linha = document.createElement('tr')
                linha.style.cursor = "pointer"
                
                linha.setAttribute('scope', 'row')
                const colunas = [cliente.id, cliente.nome, cliente.email, cliente.cpf]
                colunas.forEach(coluna => {
                    const celula = document.createElement('td')
                    celula.textContent = coluna
                    celula.addEventListener('click', () =>{
                        editar(cliente.id, 0)
                    })
                    linha.appendChild(celula)
                })
                const acoes = document.createElement('td')
                const iconeEditar = document.createElement('span')
                const iconeExcluir = document.createElement('span')
                iconeEditar.setAttribute('data-feather', 'edit')
                iconeEditar.classList.add('data-feather', 'mr-3')
                iconeEditar.setAttribute('style', 'cursor: pointer')
                iconeEditar.setAttribute('onclick', 'editar(' + cliente.id + ','+ 1 +')')
                iconeExcluir.setAttribute('data-feather', 'trash')
                iconeExcluir.classList.add('data-feather', 'ml-2')
                iconeExcluir.setAttribute('style', 'cursor: pointer')
                iconeExcluir.setAttribute('onclick', 'excluir(' + cliente.id + ')')
    
                acoes.appendChild(iconeEditar)
                acoes.appendChild(iconeExcluir)
                linha.appendChild(acoes)
                
                table.appendChild(linha)
                
            }
        });
    }
    feather.replace()
}
window.onload = fetchGet
//excluir
function excluir(id){
    const header = document.getElementById('header')
    header.style.display = "none"
    const modal = document.getElementById('modal-confirm')

    const div = document.createElement('div')
    div.classList.add('col-md-12', 'm-4')
    const p = document.createElement('p')
    p.textContent = "Você deseja deletar este usuário?"
    const btns  = document.createElement('button')
    btns.textContent = "sim"
    btns.classList.add('btn', 'btn-light','ml-3', 'col-1')
    const btnn  = document.createElement('button')
    btnn.textContent = "não"
    btnn.classList.add('btn', 'btn-light', 'ml-3', 'col-1')

    div.append(p, btns, btnn)
    
    modal.append(div)

    btns.addEventListener('click', ()=>{
        fetch('http://127.0.0.1:8000/api/delete-cliente/'+id,{
            method: 'DELETE',
            mode: 'cors'
        }) 
        .then(response => response.json())
        .then(data => {
            console.log(data);
            window.location.href = 'painel.html'
        })
        .catch(error => {
            console.error(error);
        });
    })
    btnn.addEventListener('click', ()=>{
        div.remove()
        header.style.display = "block"
    })
}
//editar
function editar(id, editar){
    if(editar == 1){
        window.location.href = "cliente.html?id=" + id + "&editar=" + 1
    }

    if(editar == 0){
        window.location.href = "cliente.html?id=" + id + "&ver=" + 1
    }
    
}
