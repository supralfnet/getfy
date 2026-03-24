# Changelog


## [1.0.4] - 24-03-2026

### Security

- Remove o fluxo `manual` do checkout público: `payment_method` obrigatório e apenas `pix`, `card`, `boleto` ou `pix_auto`; pedidos já não podem ser concluídos sem gateway (bloqueio de requisições forjadas).
- Ajusta o checkout Vue para não fazer fallback para método inválido e validar método antes do envio.
- API (Checkout Pro e Payments): cessão de acesso ao produto só após confirmação de pagamento (webhook ou cartão aprovado); cartão síncrono continua a conceder acesso no momento da aprovação.
- Área de membros: login sem senha não aceita contas com acesso ao painel (admin/infoprodutor), evitando escalação de sessão.
- Sessões de checkout da API: validação do `amount` em relação ao preço do produto/oferta/plano quando há `product_id` (tolerância numérica); oferta e plano devem pertencer ao produto.
- Webhooks: cancelamento, rejeição e reembolso exigem reconfirmação via API do gateway; política configurável em `config/webhooks.php` (`reconfirm_fail_policy`) — Mercado Pago com fail-closed por omissão quando a reconfirmação falha.
- Refatora concessão de acesso pós-pagamento em `Order::grantPurchasedProductAccessToBuyer()` (incluindo itens do pedido).

### Added

- `config/webhooks.php` com política `reconfirm_fail_policy` e variáveis de ambiente associadas.
- Testes de feature: checkout público (rejeição de `manual` e obrigatoriedade de `payment_method`) e reconfirmação em webhooks destrutivos.
- Helper `createTestProduct()` na suíte de testes para SQLite (compatível com `products.id` inteiro em ambiente de testes).
- Testes de feature `AccessEmailPasswordBlockTest` (Mail fake) para o bloco de credenciais e ausência de duplicação.
- Testes de feature: `payment_intent.succeeded` (Stripe) em `ProcessPaymentWebhook` e prop `has_bearer_token` na página Integrações.
- Gateway **CajuPay** (PIX, Brasil): novo provedor com `CajuPayDriver` — autenticação **X-API-Key** / **X-API-Secret** (par gerado no painel CajuPay, API / Chaves), campos **chave pública** e **chave secreta** em Integrações → Gateways; criação de cobrança PIX (`POST /api/payments/pix` com cabeçalho `Idempotency-Key`), consulta de status em `GET /api/payments` para polling do checkout e reconfirmação em fluxos de webhook; teste de conexão via `GET /api/wallet/balance`; registrado em `config/gateways.php` como primeiro gateway na lista e na ordem padrão de redundância PIX (`default_order`); imagem `public/images/gateways/cajupay.png`; URL base opcional `CAJUPAY_API_BASE_URL` no `.env`.
- Integrações (gateways): no card e no painel lateral da CajuPay, badge **D+0** ao lado do nome com tooltip sobre liquidação no mesmo dia útil.

### Fixed

- Testes em SQLite ao criar produtos sem conflito de tipo de `id` (mass assignment / UUID em ambiente diferente do MySQL).
- E-mail de acesso à área de membros passa a exibir de forma destacada o e-mail e a senha provisória: o template padrão inclui um bloco "Guarde seus dados de acesso" com `{email_cliente}` e `{senha}`; pedidos com template personalizado sem o placeholder `{senha}` recebem o mesmo bloco ao final do HTML (sem duplicar quando o template já usa `{senha}`).
- Webhook de pagamento Stripe (`payment_intent.succeeded`): `ProcessPaymentWebhook` trata o mesmo fluxo de pagamento confirmado que `order.paid`, disparando conclusão do pedido e `OrderCompleted` após reconfirmação na API.
- Página Integrações: lista de webhooks inclui `has_bearer_token` (como na API JSON), permitindo exibir o aviso de token já salvo após reload; texto de ajuda no formulário explica que o token não é exibido por segurança.
- Logs em `ProcessPaymentWebhook` para pedido não encontrado, lock concorrente, pedido já concluído e reconfirmação de gateway diferente de pago.

## [1.0.3] - 18-03-2026

### Fixed

- Ajusta a responsividade da listagem de vendas (/vendas) no mobile.
- Melhora o alinhamento visual dos cards da listagem de vendas no mobile.
- Ajusta a responsividade da listagem de assinaturas no mobile.
- Ajusta a responsividade da listagem de alunos (/produtos/alunos) no mobile.
- Evita autofill de e-mail no campo de busca de alunos (/produtos/alunos).
- Evita disparo automático da busca ao digitar (mín. 3 caracteres) em /vendas e /produtos/alunos.
- Evita autofill do e-mail ao cadastrar novo aluno (Produtos e Área de Membros).
- Corrige mensagens de validação para não exibir chaves (ex.: validation.unique).
- Ajusta rolagem horizontal das abas de configurações no mobile.
- Ajusta responsividade da seção de Storage em Configurações no mobile.
- Oculta a aba Traduções em Configurações no mobile.
- Corrige link do e-mail de acesso para não exigir senha na área de membros.
- Corrige reenvio do e-mail de acesso pela lista de vendas quando já enviado.
- Exibe indicador de token salvo em webhooks e melhora edição sem expor o valor.
- Garante disparo e logs de webhooks em eventos reais mesmo sem worker de fila.
- Ajusta teste de SMTP (STARTTLS) para respeitar verificação TLS e portas comuns.

## [1.0.2] - 18-03-2026

### Added

- Adiciona suporte a PIX automático no Checkout Pro da API de Pagamentos.

### Changed

- Melhora a detecção de métodos disponíveis no Checkout Pro da API (considera gateways conectados e fallback).

### Fixed

- Corrige disparo do webhook de confirmação (order.completed) na API quando o pedido não possui produto.
- Corrige exibição de métodos no Checkout Pro da API (PIX, cartão e boleto) para não ficar apenas em PIX.
- Corrige busca de CEP no checkout (ViaCEP) removendo headers que causavam bloqueio por CORS e evitando loader travado.

## [1.0.1] - 15-03-2026

### Added

- Adiciona URL de retorno padrão por aplicação na API de Pagamentos.
- Adiciona página de obrigado exclusiva do Checkout Pro (API) com redirecionamento.
- Adiciona personalização do rodapé do checkout por produto (logo, e-mail e texto).
- Adiciona suporte a múltiplos PDFs em aulas do tipo material na área de membros.
- Adiciona liberação programada de módulos e aulas por dias ou data.
- Adiciona busca e filtros avançados na página de vendas (/vendas).
- Adiciona busca de alunos por nome/e-mail na página de alunos.
- Adiciona script update.sh para atualização manual em VPS (Docker).

### Fixed

- Corrige erro na migration de UUID em products ao dropar PRIMARY KEY em tabelas pivot com FKs.
- Corrige redirecionamento pós-pagamento do Checkout Pro (API) para não cair no obrigado de produtos.
- Corrige botão na página de obrigado para produto "Somente link de pagamento".
- Corrige venda no cartão (Mercado Pago) ficando como pendente após aprovação.
- Corrige instabilidade/timeouts ao gerar PIX no Spacepag em alguns servidores (retry/IPv4 e timeouts configuráveis).
- Reduz tempo do retry do Spacepag em timeouts para não travar o checkout.
- Força HTTP/1.1 no Spacepag/Sapcepag e sanitiza base_url para evitar timeouts por HTTP/2 e URLs mal coladas.
- Corrige atualização automática em VPS/Docker quando o Git bloqueia o repositório por "dubious ownership".
- Corrige atualização automática no Docker quando vendor/bin/composer não existe.
- Corrige erro 500 em produção quando QUEUE_CONNECTION está inválido (ex.: file).
- Corrige instalador Docker quando há alterações locais no repositório (stash obrigatório antes do checkout).
- Corrige editor de checkout resetando gateways selecionados por método de pagamento ao salvar.
- Bloqueia acesso ao /docker-setup após a configuração inicial do domínio.
- Evita erro 500 no checkout quando falha ao gerar pagamento e a ordem não pode ser deletada.
- Reduz lentidão do checkout em falhas de gateways com timeouts menores e limite total por tentativa.
- Corrige dropdown de ações na listagem de vendas (/vendas) para abrir sobre a tabela.
- Corrige preview do editor de checkout para respeitar oferta/plano ao renderizar.
- Corrige player de vídeo da área de membros (CSP do YouTube, fullscreen e fallback).
- Força orientação landscape no fullscreen do player no mobile (quando suportado).

## [1.0.0] - 09-03-2026

### Added

Lançamento inicial.
