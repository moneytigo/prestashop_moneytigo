{*
    * NOTICE OF LICENSE
    *
    * This file is create by Ips
    * For the installation of the software in your application
    * You accept the licence agreement.
    *
    * You must not modify, adapt or create derivative works of this source code
    *
    *  @author    Ips
    *  @copyright 2018-2021 IPS INTERNATIONNAL SAS
    *  @license   moneytigo.com
*}
<div class=" panel card mt-2 d-print-none">
  <div class="card-header">
    <div class="row">
      <div class="col-md-6">
        <h3 class="card-header-title"> {l s='MoneyTigo transaction details' mod='moneytigo'} </h3>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table class="table" width="100%">
      <thead>
        <tr>
          <th> {l s='Transaction ID' mod='moneytigo'} </th>
          <th> {l s='Date' mod='moneytigo'} </th>
          <th> {l s='Amount' mod='moneytigo'} </th>
          <th> {l s='State' mod='moneytigo'} </th>
        </tr>
      </thead>
      <tbody>
        <tr> {foreach from=$transac item=data}
          <td> {$data|escape:'htmlall':'UTF-8'} </td>
          {/foreach} </tr>
      </tbody>
    </table>
  </div>
</div>
