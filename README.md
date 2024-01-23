# Restaurant API

Sistema de Notificações em Tempo Real para Pedidos de Restaurantes

## Instalação

Instale as dependências necessárias

```bash
    composer install
```

Para Subir os Containers atreáves do Laravel Sail

```bash
    sail up -d
```

Execute o script com os comandos artisan

```bash
    sail bash sail-commands.sh
```

Caso necessário, certifique-se de ter permissões adequadas para executar o script

```bash
    chmod +x sail-commands.sh
```


## Referência de API

#### Headers
```:
  Accept: application/json
  Content-Type: application/json
```
#### Cria um novo pedido

```http
  POST /api/orders/create
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `client_name` | `string` | **Required** |
| `table` | `integer	` | **Required** |
| `items` | `array` | **Required** |
| `items.item_id` | `integer	` | **Required** |
| `items.quantity` | `integer	` | **Required** |

#### Obter Pedidos em Processamento
```http
  GET /api/orders/processing
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `per_page` | `string` | **Optional** (default 10)|
| `order_by` | `integer	` | **Optional** (default id)|
| `order_by_type` | `array` | **Optional** (default asc)|
| `page` | `integer	` | **Optional** (default 1)|
| `search_term` | `integer	` | **Optional**|

#### Consultar Pedido

```http
  GET /api/orders/${id}/consult
```

#### Cancelar Pedido

```http
  DELETE /api/orders/${id}/cancel
```
