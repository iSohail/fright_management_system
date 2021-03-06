<template>
  <div>
    <v-alert color="light-blue darken-3 mb-0" dark dense tile flat>
      <v-breadcrumbs class="py-3" dark :items="bread_crumb_items">
        <template v-slot:item="{ item }">
          <v-breadcrumbs-item>{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
        </template>
      </v-breadcrumbs>
    </v-alert>
    <v-card style="height: 100%; min-height: 100vh" dark flat tile>
      <v-card-title class="px-8 pt-8 headline">CUSTOMER ACCOUNTS</v-card-title>
      <v-card-text>
        <v-container class="px-4 py-2">
          <!-- START OF FORM  -->
          <v-form ref="form">
            <v-row class="pr-4">
              <v-col cols="12" sm="6">
                <v-autocomplete
                  v-model="customer"
                  :items="customers"
                  :disabled="isUpdating"
                  chips
                  dense
                  filled
                  item-text="name"
                  item-value="id"
                  label="Customer"
                  required
                >
                  <template v-slot:selection="data">
                    <v-chip>{{ data.item.name }}</v-chip>
                  </template>
                  <template v-slot:item="data">
                    <template v-if="typeof data.item !== 'object'">
                      <v-list-item-content v-text="data.item"></v-list-item-content>
                    </template>
                    <template v-else>
                      <v-list-item-avatar v-html="data.item.no"></v-list-item-avatar>
                      <v-list-item-content>
                        <v-list-item-title v-html="data.item.name"></v-list-item-title>
                        <v-list-item-subtitle v-html="data.item.company"></v-list-item-subtitle>
                      </v-list-item-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-col>
              <v-col cols="12" sm="3">
                <v-btn class="mt-sm-6" @click="search_customer">Search</v-btn>
              </v-col>
            </v-row>
            <v-divider horizontal dark></v-divider>

            <!-- READ ONLY FIELDS  -->
            <v-row>
              <v-col>
                <v-text-field dense filled label="Total Amount Paid" v-model="amount_paid" readonly></v-text-field>
              </v-col>
              <v-col>
                <v-text-field
                  dense
                  filled
                  label="Total Amount Pending"
                  v-model="amount_pending"
                  readonly
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
          <!-- end of Form  -->

          <!-- Table Code    -->

          <v-card>
            <v-card-title>
              Ledgers
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="ledgers"
              :search="search"
              show-expand
              item-key="id"
              :loading="loading"
              loading-text="Loading... Please wait"
              :single-expand="singleExpand"
              :expanded.sync="expanded"
            >
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" class="black">
                  <v-row>
                    <v-subheader>Ledger Details</v-subheader>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="4">
                      <v-text-field
                        label="Gross Total"
                        placeholder="Gross Total"
                        v-model="item.gross_total"
                        readonly
                        outlined
                        dense
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        label="Income Tax"
                        placeholder="Income Tax"
                        v-model="item.income_tax"
                        readonly
                        outlined
                        dense
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-text-field
                        label="Sales Tax"
                        placeholder="Sales Tax"
                        v-model="item.sales_tax"
                        readonly
                        outlined
                        dense
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </td>
              </template>
            </v-data-table>
          </v-card>
          <v-snackbar v-model="snackbar">
            {{ text }}
            <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
          </v-snackbar>
        </v-container>
      </v-card-text>
    </v-card>
  </div>
</template>


<script>
export default {
  data: () => ({
    bread_crumb_items: [
      {
        text: "Accounts"
      },
      {
        text: "Customer Account"
      }
    ],
    valid: true,
    isUpdating: false,
    amount_paid: 0,
    amount_pending: 0,
    search: "",
    customer: [],
    customers: [],
    snackbar: false,
    text: "",
    loading: false,
    paid: false,
    search: "",
    expanded: [],
    singleExpand: false,
    headers_bilties: [
      {
        text: "Builty No",
        align: "left",
        value: "no",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Manual",
        value: "manual",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Sender",
        value: "sender",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Receiver",
        value: "receiver",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Status",
        value: "status",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Amount",
        value: "total_amount",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Created",
        value: "created_at",
        class: "light-blue darken-3 white--text"
      }
    ],
    headers: [
      {
        text: "Ledger No",
        align: "left",
        value: "no",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Description",
        value: "description",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "A/R",
        value: "account_receivable",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Paid",
        value: "amount_paid",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Pending",
        value: "pending_amount",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Status",
        value: "status",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Created",
        value: "created_at",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Paid  on",
        value: "paid_on",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "",
        value: "data-table-expand",
        class: "light-blue darken-3 white--text"
      }
    ],
    ledgers: [],
    bilties: []
  }),

  created() {
    this.initialize();
  },
  watch: {
    isUpdating(val) {
      if (val) {
        setTimeout(() => (this.isUpdating = false), 3000);
      }

      // return this.mySelectDataValue == 0 || null // some comparison to determine its been selected
    }
  },

  methods: {
    changeDateFormat(date) {
      if (date) {
        let dateSplit = date.split("-");
        return dateSplit[2] + "-" + dateSplit[1] + "-" + dateSplit[0];
      } else {
        return "-";
      }
    },
    initialize() {
      this.getCustomers();
    },
    hasValue: function() {
      return this.mySelectDataValue == 0 || null; // some comparison to determine its been selected
    },
    search_customer() {
      if (this.customer) {
        this.getLedgers(this.customer);
        this.fetchAccounts(this.customer);
      }
    },
    getCustomers() {
      this.$http({
        url: `customer`,
        method: "GET"
      }).then(
        res => {
          let customers = [];
          for (let customer of res.data) {
            customers.push({
              id: customer.id,
              no: customer.attributes.customer_no,
              name: customer.attributes.name,
              company: customer.attributes.company,
              cellNo: customer.attributes.cell_no,
              perKg: customer.attributes.per_kg_rate,
              perCbm: customer.attributes.per_cbm_rate,
              perPackage: customer.attributes.per_pck_rate
            });
          }
          this.customers = customers;
        },
        () => {}
      );
    },
    fetchAccounts(customer_id) {
      this.$http({
        url: `ledger/accounts/customer/${customer_id}`,
        method: "GET"
      }).then(
        res => {
          this.amount_paid = res.data.amount_paid;
          this.amount_pending = res.data.amount_pending;
        },
        () => {}
      );
    },
    getLedgers(customer_id) {
      this.$http({
        url: `ledgers/${customer_id}`,
        method: "GET"
      }).then(
        res => {
          let ledgers = [];
          for (let ledger of res.data) {
            let ledger_data = {
              id: ledger.id,
              no: ledger.attributes.ledger_no,
              description: ledger.attributes.description,
              income_tax: ledger.attributes.income_tax,
              sales_tax: ledger.attributes.sales_tax,
              gross_total: ledger.attributes.bilty_total,
              account_receivable: ledger.attributes.account_receivable,
              amount_paid: ledger.attributes.amount_paid,
              pending_amount: ledger.attributes.pending_amount,
              status: ledger.attributes.status,
              paid_on: this.changeDateFormat(
                ledger.attributes.paid_on.slice(0, 10)
              ),
              created_at: this.changeDateFormat(
                ledger.attributes.created_at.slice(0, 10)
              ),
              bilties: []
            };
            ledgers.push(ledger_data);
          }
          this.ledgers = ledgers;
        },
        () => {
          console.log("error occured");
        }
      );
    }
  }
};
</script>