# OverdriveAPI
Teste técnico para a vaga de Desenvolvedor Php/Laravel.

Funcionalidades:
<ul>
<li>Pessoa</li>
<ul>
<li>Cadastrar Pessoa com os campos: id, nome, documento, telefone, usuário e status</li>
<li>Permitir edição de pessoa</li>
<li>Permitir consultar lista de pessoas cadastradas</li>
<li>Consultar pessoa pelo nome</li>
</ul>
<li>Histórico do Status
<ul>
<li>Para cada alteração no cadastro da pessoa, deve-se manter o histórico da atualização do status</li>
<li>Informações: id da pessoa, status, data e hora (Timestamp) e usuário que fez a alteração</li>
<li>Permitir consulta do histórico pelo id da pessoa</li>
</ul>
</li>
</ul>

<h3 style="margin-bottom: 0; font-weight: bolder; color: #c92f2f">Links Importantes:</h3>
GitProject: <a href="https://github.com/users/Edgarvital/projects/3/views/1">Link</a>
<br>
Artefatos (Modelo entidade relacionamentos e Dados do Postman): <a href="https://github.com/Edgarvital/OverdriveAPI/tree/main/Artefatos">Link</a>

<h3>Observações:</h3>
Como não era necessário ter a parte da autenticação, mas a entidade pessoa tinha uma ligação com o usuário, mantive a tabela user que o próprio laravel fornece, removi a regra de unique no email e fiz a instanciação de user sempre que uma pessoa é criada.<br>
Inferi também que se o user_id não for informado durante uma atualização do status de uma pessoa, a própria pessoa está atualizando o status e seu user será referênciado quando o histórico do status for criado.<br>
Sobre o campo data e hora do histórico status, optei por manter o timestamp que o próprio laravel cria na criação do migrate<br>
A rota do swagger é: localhost:8000/api/docs
