Faça uma auditoria de segurança completa neste projeto PHP. Analise todo o código-fonte buscando:

1. INJEÇÃO SQL
   - Uso de mysql_query, mysqli_query ou PDO com concatenação direta de strings
   - Queries sem prepared statements / parâmetros bindados
   - Uso inseguro de $_GET, $_POST, $_REQUEST diretamente em queries

2. XSS (Cross-Site Scripting)
   - Dados do usuário impressos com echo/print sem htmlspecialchars() ou htmlentities()
   - Uso de funções como innerHTML no JS gerado pelo PHP sem sanitização

3. INCLUSÃO DE ARQUIVOS (LFI/RFI)
   - Uso de include, require, include_once, require_once com variáveis controladas pelo usuário
   - allow_url_include ativado

4. UPLOAD DE ARQUIVOS
   - Falta de validação de extensão/mime-type real (não confiar em $_FILES['type'])
   - Arquivos salvos em pasta acessível publicamente sem checagem
   - Falta de renomeação de arquivos (permite sobrescrever/executar .php)

5. EXECUÇÃO DE COMANDOS
   - Uso de exec(), shell_exec(), system(), passthru(), popen() com entrada do usuário

6. DESERIALIZAÇÃO INSEGURA
   - Uso de unserialize() com dados vindos do usuário (risco de PHP Object Injection)

7. AUTENTICAÇÃO E SESSÕES
   - Senhas com md5()/sha1() em vez de password_hash()/password_verify()
   - Comparação de senhas/tokens com == em vez de hash_equals() (timing attack)
   - session_id previsível, session fixation
   - Cookies de sessão sem HttpOnly, Secure, SameSite

8. CSRF
   - Formulários e ações de estado (POST) sem token CSRF

9. GESTÃO DE SEGREDOS
   - Credenciais de banco de dados hardcoded no código
   - Arquivo .env versionado no git
   - Chaves de API expostas

10. CONFIGURAÇÃO DO PHP/SERVIDOR
    - display_errors ativado em produção (vaza stack trace e caminhos)
    - register_globals (se for código antigo)
    - Versão do PHP desatualizada/sem suporte
    - Falta de headers de segurança (CSP, X-Frame-Options, X-Content-Type-Options)

11. DEPENDÊNCIAS (Composer)
    - Bibliotecas desatualizadas com CVEs conhecidas no composer.json/composer.lock

12. VALIDAÇÃO DE ENTRADA
    - Falta de validação de tipo/formato antes de processar dados do usuário
    - Uso de eval() com qualquer dado externo

Para cada problema encontrado, informe:
- Arquivo e linha
- Severidade (crítica / alta / média / baixa)
- Explicação do risco
- Correção sugerida com exemplo de código PHP

Ao final, gere um resumo priorizado por severidade e uma lista de "quick wins" (correções fáceis e de alto impacto).