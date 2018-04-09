# Manual de Uso: Módulo Push Intelipost

[![N|Solid](https://image.prntscr.com/image/E8AfiBL7RQKKVychm7Aubw.png)](http://www.intelipost.com.br)

## Introdução
O módulo Push Intelipost é responsável por enviar as entregas criadas no Magento para a Intelipost. 
Este processo é indispensável para o uso do Rastreamento e Gestão de Despacho da nossa ferramenta.

Este manual foi divido em três partes:

  - [Instalação](#instalação): Onde você econtrará instruções para instalar nosso módulo.
  - [Configurações](#configurações): Onde você encontrará o caminho para realizar as configurações e explicações de cada uma delas.
  - [Uso](#uso): Onde você encontrará a maneira de utilização de cada uma das funcionalidades.
  - [Nota Fiscal](#nota-fiscal): Seção dedicada a descrever a tabela de Notas Fiscais criada pelo módulo e também como alimentá-la por API.
  
## Instalação
> É recomendado que você tenha um ambiente de testes para validar alterações e atualizações antes de atualizar sua loja em produção.

> A instalação do módulo é feita utilizando o Composer. Para baixar e instalar o Composer no seu ambiente acesse https://getcomposer.org/download/ e caso tenha dúvidas de como utilizá-lo consulte a [documentação oficial do Composer](https://getcomposer.org/doc/).

Navegue até o diretório raíz da sua instalação do Magento 2 e execute os seguintes comandos:

```
bin/composer require intelipost/magento2-push   // Faz a requisição do módulo da Intelipost
bin/magento module:enable Intelipost_Push       // Ativa o módulo
bin/magento setup:upgrade                       // Registra a extensão
bin/magento setup:di:compile                    // Recompila o projeto Magento
```

## Configurações
Para acessar o menu de configurações, basta seguir os seguintes passos:

No menu à esquerda, acessar **Lojas** -> **Configuration**:

[![N|Solid](https://image.prntscr.com/image/PsXopRB7Qlq6ZTF9ucwHXw.png)](http://www.intelipost.com.br) 

A partir do menu de configurações, clicar no tópico **Intelipost** -> **Push**:

[![N|Solid](https://i.snag.gy/ozX5J3.jpg)](http://www.intelipost.com.br) 

As configurações do módulo foram dividas em três etapas:

  - Atributos
  - Status dos pedidos
  - Cron Config
  
A seguir vamos falar tudo que você precisa saber de cada uma delas.
___
### Atributos
Nesta seção, abrimos espaço para o cliente definir os parâmetros que ele criou para informações que não existem no Magento por padrão.

#### Federal tax payer id:
Neste atributo deve ser selecionado qual a propriedade utilizada para o CPF.

![N|Solid](https://i.snag.gy/PeQ0tv.jpg)

Note que o campo escolhido no exemplo não é o correto. Você deve conversar com o representante da loja e descobrir em qual propriedade é registrado o CPF do cliente final.

___
### Status dos pedidos

Nesta seção, deverá  realizado as configurações de movimentação dos pedidos. Isto é, quando o pedido deve ser enviado para a Intelipost ou quando deve ser despachado.

#### Magento trigger status to create:
Nessa configuração deve ser selecionado o Status Magento do pedido em que ele deve ser criado na Intelipost. Nesta lista, aparecerão todos os status disponíveis, até mesmo os status customizados.

![N|Solid](https://i.snag.gy/nCGtUP.jpg)

No exemplo, está selecionado “Processing”, que é o status do Magento logo após o faturamento do pedido. 

#### Magento status after create:
Nessa configuração deve ser selecionado o Status Magento que o pedido deverá receber após o envio para a Intelipost.

![N|Solid](https://i.snag.gy/6t9vsp.jpg)

No exemplo, está selecionado o status “Criado na Intelipost”, que é um status customizado por mim no nosso ambiente Magento.

#### Create and Ship:
Se esta configuração estiver marcada como "Sim", quando o pedido for enviado para a Intelipost, ele receberá a data de despacho igual ao momento do envio.
Agora, se esta configuração for marcada como “Não”, será disponibilizada uma nova configuração: **Magento trigger status to ship**.
Semelhante à “Magento trigger status to create”, você deve selecionar o Status Magento em que o pedido deverá ser marcado como despachado na Intelipost.

![N|Solid](https://i.snag.gy/6aTMv8.jpg)

No exemplo, está selecionado “Complete”, que é o Status Magento logo após a criação das entregas no Magento.

___

### Cron Config

Nesta seção faremos as configurações dos eventos cronológicos do módulo. Isto é, decidiremos em quais condições os pedidos serão enviados automaticamente para a Intelipost.

#### Use cron to create orders:
Habilita o envio das entregas para a Intelipost a partir de um processo agendável.
Caso essa configuração for marcada como "Sim", será disponibilizado outras três configurações:
- **Cron status to create**: Deverá ser configurado o Status Magento em que o pedido será enviado para Intelipost.
- **Order quantity to create**: Deverá ser estipulado a quantidade de pedidos necessária para envio para a Intelipost.
- **Frequency to create**: Deverá ser selecionado o intervalo de tempo, em minutos, que o processo será executado.

![N|solid](https://image.prntscr.com/image/DFuOid56Qx6F-xon6clWBQ.png)

#### Use cron to ship orders:
Muito semelhante à configuração anterior, essa configuração habilita o despacho dos pedidos a partir de um processo agendável. aso essa configuração for marcada como "Sim", será disponibilizado outras três configurações:
- **Cron status to ship**: Deverá ser configurado o Status Magento em que o pedido será marcado como despachado na Intelipost.
- **Order quantity to ship**: Deverá ser estipulado a quantidade de pedidos necessária para realizar o despacho na Intelipost.
- **Frequency to ship**: Deverá ser selecionado o intervalo de tempo, em minutos, que o processo será executado.


## Uso
Com a instalação do módulo, será disponibilizado uma nova tabela de pedidos.
Essa tabela pode ser consultada no menu **Intelipost** -> **Pedidos**:

![N|solid](https://image.prntscr.com/image/9JxOTir_SG65XowIVYIsjA.png)


A nova tela de pedidos apresentará todos os dados de envio gerados a partir de uma cotação da Intelipost. Também apresentará o Status Magento e o Status Intelipost de cada um deles.

![N|Solid](https://image.prntscr.com/image/rsFzTIq5SOGvKCAbW8EUWA.png)

Caso o cliente utilize a gestão de despacho com a Intelipost, ele poderá consultar os códigos de rastreamento direto do Magento. Para habilitar essa coluna na tabela, basta clicar em **Columns** -> **Tracking Code**:

![N|Solid](https://i.snag.gy/UdOkof.jpg)

O módulo Push também permite ao cliente operar de forma manual, isto é, enviar ou despachar os pedidos a partir de comandos do Magento. Esses comandos estão disponíveis também em lote.

Para realizar uma dessas ações, você deve selecionar os pedidos desejados e, em Actions, selecionar Create Orders (para criar as entregas) ou Ship Orders (para despachar as entregas).

![N|Solid](https://image.prntscr.com/image/2YogExSgQ3inZv-FcXmmkw.png)

Após ter realizado a ação, uma mensagem de erro ou sucesso aparecerá no topo da página. Além disso, ela ficará salva na coluna **Intelipost Message**. 

Obs: Vale ressaltar que toda tentativa de criação ou despacho, seja de forma manual ou automática, salvará uma **Intelipost Message**.

![N|Solid](https://image.prntscr.com/image/e-01yw18SxCDNcihnZTXWg.png)

## Nota Fiscal

O módulo contempla uma tabela de Notas Fiscais (intelipost_invoice). Os campos desta tabela são:

| Campo | Descrição |Tipo|
| ------ | ------ |------|
| id | Identificador primário |integer (auto increment)|
| invoice_number | Número da nota fiscal |varchar|
| order_number | Número do pedido que a nota está relacionada |varchar|
| invoice_series | Série da nota fiscal |varchar|
| invoice_key | Chave da nota fiscal |varchar|
|invoice_date| Data de criação da Nota Fiscal | current_timestamp|
| invoice_total_value | Valor total da nota fiscal |varchar|
| invoice_products_value | Valor dos produtos |varchar|
| invoice_cfop | CFOP dos produtos da nota |varchar|

Para consultar e inserir dados nessa tabela via API, você pode utilizar as seguintes métodos:

**GET** - http://{{url_da_loja}}/rest/v1/push/list  
Retornará uma lista com todas as notas salvas até o momento.

**POST** - http://{{url_da_loja}}/rest/v1/push/save  
Adicionará a nota fiscal à tabela.

Request_body:
```json
{
    "invoice": [
        {
            "invoice_number": "10",
            "order_number": "000000006",
            "invoice_series": "1",
            "invoice_key": "01234567890123456789012345678901234567891234",
            "invoice_date": "2017-11-28 19:47:35",
            "invoice_total_value": "10.20",
            "invoice_products_value": "10.00",
            "invoice_cfop": "1612"
        }
    ]
}
```

Obs: Para realização das chamadas, deverá ser passado o token de autenticação do usuário. Para mais detalhes, consulte a [documentação do Magento](http://devdocs.magento.com/guides/v2.1/get-started/rest_front.html).
