<template>
  <div id="app">
    <v-app id="inspire">
      <v-container class="padding-on-print" style="min-width: 1000px !important">
        <v-toolbar color="white" flat tile>
          <v-toolbar-items>
            <v-btn light text @click="print">
              <v-icon>mdi-printer</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-row>
          <v-col>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="title blue--text">NASEEB GOODS TRANSPORT CO</v-list-item-title>
                <v-list-item-subtitle
                  class="font-weight-regular black--text body-2"
                >PLOT#292, 293 294 ST#2, GT#4</v-list-item-subtitle>
                <v-list-item-subtitle
                  class="font-weight-regular black--text body-2"
                >Truck Stand, Hawksbay Road, Karachi</v-list-item-subtitle>
                <v-list-item-subtitle class="font-weight-regular black--text body-2">(021)32352185-7</v-list-item-subtitle>
                <v-list-item-subtitle
                  class="font-weight-regular black--text body-2"
                >naseebgoods@hotmail.com</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-col>
          <v-col>
            <v-row justify="end" class="my-4">
              <v-subheader class="display-1 blue--text mr-2">INVOICE</v-subheader>
            </v-row>
            <v-simple-table style="width: 70%; float:right">
              <template v-slot:default>
                <thead class="blue lighten-4">
                  <tr>
                    <th class="text-left subtitle-2 black--text">Invoice Number</th>
                    <th class="text-left subtitle-2 black--text">Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{invoice_no}}</td>
                    <td>{{created}}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-simple-table light>
              <template v-slot:default>
                <thead class="blue lighten-4">
                  <tr>
                    <th class="text-left subtitle-2 black--text">Bill To</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <v-list-item class="pl-2">
                        <v-list-item-content>
                          <v-list-item-subtitle
                            class="body-2 black--text"
                          >{{customer_name.toUpperCase()}}</v-list-item-subtitle>
                          <v-list-item-subtitle
                            class="body-2 black--text"
                          >{{customer_company.toUpperCase()}}</v-list-item-subtitle>
                          <v-list-item-subtitle class="body-2 black--text">{{customer_cell}}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-col>
          <v-col>
            <v-simple-table class="text-center" style="width: 70%; float:right">
              <template v-slot:default>
                <thead class="blue lighten-4">
                  <tr>
                    <th class="text-center subtitle-2 black--text">Customer ID</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{customer_no}}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-data-table
              light
              :headers="headers_selected_item"
              :items="selected_items"
              mobile-breakpoint="NaN"
              hide-default-footer
              disable-pagination
              disable-sort
              disable-filtering
              flat
              class="table_data_style"
            ></v-data-table>
          </v-col>
        </v-row>
        <v-row justify="end">
          <v-simple-table>
            <template v-slot:default>
              <tbody>
                <tr class="blue lighten-5">
                  <td class="title black--text font-weight-medium subtitle-1">Gross Total Rs.</td>
                  <td
                    class="title black--text font-weight-medium subtitle-1"
                  >{{selected_gross_amount}}</td>
                </tr>
                <tr v-if="taxed_on === 'Sales Tax' || taxed_on === 'Both'">
                  <td class="title mb-2 pr-4 subtitle-2">+ Sales Tax: Rs.</td>
                  <td class="title mb-2 pr-4 subtitle-2">{{selected_sales_tax_amount}}</td>
                </tr>
                <tr v-if="taxed_on === 'Income Tax' || taxed_on === 'Both'">
                  <td class="title mb-2 pr-4 subtitle-2">- Income Tax: Rs.</td>
                  <td class="title mb-2 pr-4 subtitle-2">{{selected_income_tax_amount}}</td>
                </tr>
                <tr class="blue lighten-5">
                  <td class="title black--text font-weight-medium subtitle-1">Net Total: Rs.</td>
                  <td class="title black--text font-weight-medium subtitle-1">{{selected_net_total}}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-row>
        <!-- <v-row justify="end" align="end">
          <p
            class="title black--text mb-2 pr-4 font-weight-medium subtitle-1"
          >Gross Total: Rs.{{selected_gross_amount}}</p>
        </v-row>
        <v-row v-if="taxed_on === 'Sales Tax' || taxed_on === 'Both'" justify="end" align="end">
          <p class="title mb-2 pr-4 subtitle-2">+ Sales Tax: Rs.{{selected_sales_tax_amount}}</p>
        </v-row>
        <v-row v-if="taxed_on === 'Income Tax' || taxed_on === 'Both'" justify="end" align="end">
          <p class="title mb-2 pr-4 subtitle-2">- Income Tax: Rs.{{selected_income_tax_amount}}</p>
        </v-row>
        <v-row justify="end" align="end">
          <v-subheader
            class="title black--text font-weight-medium subtitle-1"
          >Net Total: Rs.{{selected_net_total}}</v-subheader>
        </v-row>-->
      </v-container>
    </v-app>
  </div>
</template>

<script>
export default {
  data() {
    return {
      invoice_no: "",
      taxed_on: "None",
      created: "",
      selected_items: [],
      income_tax: "",
      sales_tax: "",
      bilty_total: "",
      account_receivable: "",
      selected_income_tax_amount: "",
      selected_sales_tax_amount: "",
      selected_gross_amount: "",
      selected_net_total: "",
      customer_name: "",
      customer_company: "",
      customer_cell: "",
      customer_no: "",
      headers_selected_item: [
        {
          text: "Date",
          align: "left",
          value: "date",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Bilty no",
          value: "bilty_no",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Description",
          value: "description",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Receiver",
          value: "receiver",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Route",
          value: "route",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Weight",
          value: "weight",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Rent",
          value: "rent",
          class: "blue lighten-4 black--text subtitle-2"
        }
      ]
    };
  },
  created() {
    let invoice = this.$store.getters.getInvoiceById(this.$route.query.id);
    if (invoice) {
      this.invoice_no = invoice.invoice_no;
      this.created = invoice.created;
      this.taxed_on = invoice.taxed_on;
      this.selected_items = invoice.selected_items;
      this.selected_income_tax_amount = invoice.income_tax;
      this.selected_sales_tax_amount = invoice.sales_tax;
      this.selected_gross_amount = invoice.bilty_total;
      this.selected_net_total = invoice.account_receivable;
      this.customer_name = invoice.customer_data.name;
      this.customer_company = invoice.customer_data.company;
      this.customer_cell = invoice.customer_data.cellNo;
      this.customer_no = invoice.customer_data.no;
    }
  },
  methods: {
    print() {
      window.print();
    }
  }
};
</script>

<style scoped>
@page {
  size: auto; /* auto is the initial value */

  /* this affects the margin in the printer settings */
  margin: 5mm 5mm 5mm 5mm;
}
</style>

