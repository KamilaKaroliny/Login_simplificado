                                         Futebol em Crude.

Este projeto foi desenvolvido com o princípio de estudo sobre Crude, SQL e XAMPP, nele programamos um  software com filtros e paginação, do qual tem a função de cadastrar  jogadores, times e partidas, como se fosse um site para auxiliar jornalistas e atletas. Sua linguagem é SQL e PHP.


                        Tecnologias:
-XAMPP
-PHP
-SQL (via phpMyAdmin)
-Git Hub
-Visual Code
-Bootstrap
-Html e Css

                         Estrutura do projeto:

 O Futebol em Crude é dividido nas pastas : Assets ( contém a logo), db (contém o banco de dados), includes ( com  o arquivo que conecta o código do banco ao XAMPP, o código pé do nosso projeto e o cabeçalho.) public ( com duas pastas, a de jogadores com create,delete, rede update, e a pasta de times, com a mesma coisa), style ( com o style do código) , por fim arquivos como a licença, o index e o README (do qual você está lendo).

Arquivo principais:
-Banco de dados: db.sql;
Conexão: db.php;
Css: style.css


                        Requisitos:
-XAMPP instalado;
-Navegador;
-Editor de código :VSCode, sublime..


                        Rodando:
-Clonar repositório do gitHub;
-Use um diretório do XAMPP;
-Ligue o  XAMPP  e ative o MySQL e Apache;
-Copie o banco de dados e cole no phpmyadmin;
-Configurar a conexão do banco de dados.
-Acesse o navegador.




                        Funções Principais:

Times:
-Cadastrar times: nome obrigatório
-Listar times com paginação e filtro por nome
-Editar time
-Excluir time (com aviso se houver dependências)



Jogadores:
-Cadastrar jogador com nome, posição (lista pré-definida),número da camisa (1–99), e time
-Listar jogadores com filtro por nome, posição e time
-Editar jogador
-Excluir jogador



Partidas:
-Cadastrar partidas entre dois times diferentes
-Validar: times diferentes e placares ≥ 0
-Listar partidas com filtro por time, data e resultado
-Editar partida
-Excluir partida



                        Regras

-Número da camisa: somente entre 1 e 99


-Mandante ≠ Visitante: partidas não podem ser entre o mesmo time


-Todas as listagens têm paginação (10 por página)


-Exibição de mensagens de erro amigáveis


-Confirmação antes de exclusão


                         Erros:

-Erro de conexão com o banco: verifique :db.php


-Tentativa de excluir time com jogadores/partidas: mensagem de aviso


-Campos obrigatórios: mensagens de validação no formulário



                          Testar o código:


-Acesse o menu principal via index.php


-Vá em cada módulo e:


-Crie novos registros


-Use os filtros para buscar


-Edite valores


-Tente excluir registros com dependências




                                       Créditos e Licença

Este projeto foi desenvolvido pela aluna: KamilaKaroliny.
Projetado pelo nosso professor de desenvolvimento de sistemas Ícaro Botelho
Livre para estudos e dicas de melhoria.

