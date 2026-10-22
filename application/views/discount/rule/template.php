<script id="include-product-template" type="text/x-handlebars-template">
  <tr id="include-row-{{id}}">
    <td class="middle text-center">
      <label>
        <input type="checkbox" class="ace chk-include" data-id="{{id}}" data-code="{{code}}" data-sellprice="{{sell_price}}" />
        <span class="lbl"></span>
      </label>
    </td>
    <td class="middle text-center in-no">{{no}}</td>
    <td class="middle">{{code}}</td>
    <td class="middle">{{name}}</td>
    <td class="middle text-center">
      <a href="javascript:void(0)" class="red" onclick="removeIncludeItem('{{id}}')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
    </td>
  </tr>
</script>

<script id="exclude-product-template" type="text/x-handlebars-template">
  <tr id="exclude-row-{{id}}">
    <td class="middle text-center">
      <label>
        <input type="checkbox" class="ace chk-exclude" data-id="{{id}}" data-code="{{code}}" />
        <span class="lbl"></span>
      </label>
    </td>
    <td class="middle text-center ex-no">{{no}}</td>
    <td class="middle">{{code}}</td>
    <td class="middle">{{name}}</td>
    <td class="middle text-center">
      <a href="javascript:void(0)" class="red" onclick="removeExcludeItem('{{id}}')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
    </td>
  </tr>
</script>

<script id="net-price-template" type="text/x-handlebars-template">
  <tr id="net-price-row-{{id}}">
    <td class="middle text-center">
      <label>
        <input type="checkbox" class="ace chk-net-price" data-id="{{id}}" />
        <span class="lbl"></span>
      </label>         
    </td>
    <td class="middle text-center np-no">{{no}}</td>
    <td class="middle">{{code}}</td>
    <td class="middle">{{name}}</td>
    <td class="middle text-right">{{price}}</td>
    <td class="middle text-right">
      <input type="number" class="form-control input-xs text-right net-price" data-id="{{id}}" data-code="{{code}}" value="{{sell_price}}" />
    </td>
    <td class="middle text-center">
      <a href="javascript:void(0)" class="red" onclick="removeNetPriceItem('{{id}}')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>      
    </td>
  </tr>
</script>

<script id="customer-template" type="text/x-handlebars-template">
  <tr id="customer-row-{{id}}">
    <td class="middle text-center">
      <label>
        <input type="checkbox" class="ace chk-customer" data-id="{{id}}" data-code="{{code}}" data-name="{{name}}" />
        <span class="lbl"></span>
      </label>
    </td>
    <td class="middle text-center cust-no">{{no}}</td>
    <td class="middle">{{code}}</td>
    <td class="middle">{{name}}</td>
    <td class="middle text-center">
      <a href="javascript:void(0)" class="red" onclick="removeCustomer('{{id}}')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
    </td>
  </tr>
</script>

<script id="premium-template" type="text/x-handlebars-template">
  <tr id="premium-row-{{id}}">
    <td class="middle text-center">
      <label>
        <input type="checkbox" class="ace chk-premium" data-id="{{id}}" />
        <span class="lbl"></span>
      </label>
    </td>
    <td class="middle text-center premium-no">{{no}}</td>
    <td class="middle">{{code}}</td>
    <td class="middle">{{name}}</td>
    <td class="middle text-right">{{price}}</td>
    <td class="middle text-right">
      <input type="number" class="form-control input-xs text-right premium-price" data-id="{{id}}" data-code="{{code}}" value="{{sell_price}}" />
    </td>
    <td class="middle text-center">
      <a href="javascript:void(0)" class="red" onclick="removePremiumItem('{{id}}')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
    </td>
  </tr>
</script>