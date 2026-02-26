# Diagramas de Sequência UML

## 1. Fluxo de Autenticação
```mermaid
sequenceDiagram
    participant Frontend
    participant API
    participant DB
    
    Frontend->>API: POST /login (usuário, senha)
    API->>DB: Busca usuário
    DB-->>API: Retorna dados e chave do usuário
    API->>API: Valida senha
    API->>API: Gera Access Token (15 min) e Refresh Token (7 dias)
    API-->>Frontend: Retorna Tokens via HTTPS
```

## 2. Comunicação entre Microsserviços
```mermaid
sequenceDiagram
    participant Nginx as Nginx (Proxy)
    participant PHP as PHP-FPM (Backend)
    participant Redis as Redis (Sessão/Cache)
    participant Postgres as PostgreSQL
    participant ELK as ELK Stack (Logs)
    
    Nginx->>PHP: Encaminha requisição HTTP
    PHP->>Redis: Verifica token JWT ou Cache
    Redis-->>PHP: Retorno do estado
    PHP->>Postgres: Executa Query/Mutação
    Postgres-->>PHP: Retorna dados
    PHP-xELK: Grava log de operação (Assíncrono)
    PHP-->>Nginx: Retorna resposta em JSON
```

## 3. Processo de Criptografia e Descriptografia (AES-256-GCM)
```mermaid
sequenceDiagram
    participant Controller
    participant CryptoService
    participant Cifra as OpenSSL
    
    Note over Controller,Cifra: Criptografia
    Controller->>CryptoService: encrypt(dados, chaveUsuario)
    CryptoService->>CryptoService: Gera Vetor de Inicialização (IV)
    CryptoService->>Cifra: openssl_encrypt(dados, AES-256-GCM, chaveUsuario, IV)
    Cifra-->>CryptoService: Retorna dados criptografados e TAG
    CryptoService-->>Controller: Retorna [dado_seguro, iv, tag] codificados em Base64
    
    Note over Controller,Cifra: Descriptografia
    Controller->>CryptoService: decrypt(dado_seguro, chaveUsuario, iv, tag)
    CryptoService->>Cifra: openssl_decrypt(dado_seguro, AES-256-GCM, chaveUsuario, IV, TAG)
    Cifra-->>CryptoService: Retorna dado original
    CryptoService-->>Controller: Retorna dados em texto plano
```

## 4. Ciclo de Vida de Requisições
```mermaid
sequenceDiagram
    participant Cliente
    participant Gateway as Nginx
    participant App as Aplicação
    participant Logger
    
    Cliente->>Gateway: Requisição HTTP(S) iniciada
    Gateway->>Gateway: Validação de Segurança Básica (TLS)
    Gateway->>App: Repassa requisição
    App->>App: Verifica Autenticação e Autorização (JWT)
    App->>App: Processa Regra de Negócio
    App->>App: Aplica Resolução LGPD (se aplicável)
    App->>Logger: Dispara eventos de monitoramento
    App-->>Gateway: Devolve cabeçalhos HTTP e Corpo
    Gateway-->>Cliente: Resposta final enviada ao cliente
```
