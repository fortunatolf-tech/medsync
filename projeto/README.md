# MedSync - Sistema de Gestão Hospitalar

Projeto completo em PHP 7.3 otimizado para atuação em paralelo entre Frontend e Backend.

## Estrutura de Diretórios
- `/frontend` - Interface do usuário (React/TypeScript).
- `/backend` - API Restful (PHP 7.3).
- `/.github` - Pipelines de CI/CD.
- `/ssl` - Certificados locais.

## Requisitos
- Docker e Docker Compose instalados.
- OpenSSL (Geração de certificados).
- Node.js 18+ (Para o Frontend).
- PHP Composer (Para o Backend).

## Setup Inicial (Rodando Localmente)
1. Clone este repositório: `git clone https://github.com/SEU-USUARIO/medsync.git`
2. Gere os certificados na raiz do projeto: `openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout ssl/medsync.key -out ssl/medsync.crt`
3. Instale pacotes base:
   - No `/backend`: `composer install`
   - No `/frontend`: `npm install && npm run build`
4. Suba a aplicação: `docker-compose up -d --build`

---

## Divisão de Trabalho (Desenvolvimento Simultâneo)

Como o projeto utiliza Microsserviços e APIs, o trabalho ocorre em paralelo sem bloqueios:

### Papel 1: Desenvolvedor BACKEND
**Responsabilidade:** Criar e expor os Endpoints que o frontend vai consumir.
1. **Atividade:** Navegue até a pasta `/backend`.
2. **Setup DB:** Crie as migrações (arquivos SQL) e tabelas (como *Users*, *Medical_Records*).
3. **Segurança:** Implemente e valide regras na classe `CryptoService.php` (AES-256) para garantir a criptografia de dados sensíveis na gravação.
4. **Tráfego:** Garanta que todas as novas requisições do Nginx que caem no `php-fpm:9000` sejam validadas pela classe `JwtService.php` (Access Token + Refresh Token).
5. **Regras LGPD:** Evolua as features em `/backend/src/Compliance/LgpdService.php` integrando-as nos endpoints recém criados e validando com PHPUnit.

### Papel 2: Desenvolvedor FRONTEND
**Responsabilidade:** Desenhar Telas, Fluxos de Usuário e Banners informativos.
1. **Atividade:** Navegue até a pasta `/frontend`.
2. **LGPD Visual:** Modifique ou estampe o `CookieBanner.tsx` onde for necessário na inicialização do App e implemente leitura das políticas do `PrivacyPolicy.md`.
3. **Requisições (Mocks/API):** Desenvolva as telas de Login e Registro. Chame os endpoints criados pelo backend. *Dica*: Enquanto o Backend não termina, você pode usar ferramentas como Mocks JSON ou "Postman Mock Server" simulando que o backend já retornou o JWT.
4. **Ciclo JWT:** Programe a interceptação automática do `Fetch` ou `Axios` para renovar o accessToken invisivelmente usando o Refresh Token, quando necessário.
5. **Testes e Lint:** Sempre antes do Commit, verifique as regras de código executando o Jest. A configuração base `jest.config.js` e o Husky já devem barrar o seu commit caso falhem.

## Políticas de Contribuição GitHub
- Nunca trabalhem direto na branch `main`.
- Desenvolvedor Backend: Crie branches prefixadas como `backend/feature-xyz`.
- Desenvolvedor Frontend: Crie branches prefixadas como `frontend/feature-xyz`.
- O GitHub Actions testará PSR12 (Backend) e Jest/Prettier (Frontend) em cada Pull Request para `staging`. O Merge só ocorre se TODOS os serviços passarem.
