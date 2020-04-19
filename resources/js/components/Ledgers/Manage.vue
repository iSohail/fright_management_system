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
      <v-card-title class="px-8 pt-8 headline">MANAGE LEDGER</v-card-title>
      <v-card-text>
        <v-card class="mx-4 my-2 mb-4">
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
            v-model="selected"
            :headers="headers"
            :items="ledgers"
            :search="search"
            show-select
            show-expand
            item-key="id"
            :loading="loading"
            loading-text="Loading... Please wait"
            :single-expand="singleExpand"
            :expanded.sync="expanded"
          >
            <template v-slot:item.action="{ item }">
              <v-row>
                <v-col cols="6" class="px-0 pr-2">
                  <v-btn class="primary float-right" small @click="editItem(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                </v-col>
                <v-col cols="6" class="px-0">
                  <v-btn class="red mr-sm-3" small @click="printItem(item)">
                    <v-icon>mdi-printer</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </template>
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
                <v-row>
                  <v-subheader>Customer Details</v-subheader>
                </v-row>
                <v-row v-if="item.customer">
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Number"
                      placeholder="Number"
                      v-model="item.customer.customer_no"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Name"
                      placeholder="Name"
                      v-model="item.customer.name"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Company"
                      placeholder="Company"
                      v-model="item.customer.company"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-divider></v-divider>
                <v-row>
                  <v-subheader>Bilty Details</v-subheader>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-data-table
                      :headers="headers_bilties"
                      :items="item.bilties"
                      :items-per-page="5"
                    ></v-data-table>
                  </v-col>
                </v-row>
              </td>
            </template>
          </v-data-table>
        </v-card>
        <v-card-actions>
          <v-row justify="end">
            <v-dialog v-model="paid" scrollable light max-width="600px">
              <template v-slot:activator="{ on }">
                <v-btn class="my-4 mx-4 float-right" color="primary" v-on="on">Paid</v-btn>
              </template>
              <v-card dark>
                <v-card-title
                  class="subtitle-2"
                >Status of following ledgers will be changed to Cleared</v-card-title>
                <v-divider></v-divider>
                <v-card-text style="height: 300px;">
                  <v-row>
                    <div v-if="selected.length > 0" style="width: 100%">
                      <v-simple-table height="230px" dense>
                        <template v-slot:default>
                          <thead>
                            <tr>
                              <th class="text-left"></th>
                              <th class="text-left">Ledger No</th>
                              <th class="text-left">A/R</th>
                              <th class="text-left">Status</th>
                              <th class="text-left">Created</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="item in selected" :key="item.id">
                              <td>
                                <v-icon>mdi-check-bold</v-icon>
                              </td>
                              <td>{{ item.no }}</td>
                              <td>{{ item.account_receivable }}</td>
                              <td>{{ item.status }}</td>
                              <td>{{ item.created_at }}</td>
                            </tr>
                          </tbody>
                        </template>
                      </v-simple-table>
                    </div>
                    <div v-else class="mt-4 subtitle-1 text-center" align="center">No Items selected</div>
                  </v-row>
                  <v-row justify="end" align="end">
                    <v-subheader class="subtitle-2">Total Amount: Rs.{{selected_total_amount}}</v-subheader>
                  </v-row>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                  <v-btn color="blue darken-1" text @click="paid = false">Close</v-btn>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1 float-right" text @click="paid = 'paid'">Okay</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>
        </v-card-actions>
        <v-snackbar v-model="snackbar">
          {{ text }}
          <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
        </v-snackbar>
      </v-card-text>
    </v-card>
  </div>
</template>


<script>
export default {
  data() {
    return {
      bread_crumb_items: [
        {
          text: "Ledger"
        },
        {
          text: "Manage Ledger"
        }
      ],
      snackbar: false,
      text: "",
      selected: [],
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
          text: "",
          value: "data-table-select",
          class: "light-blue darken-3 white--text"
        },
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
        // {
        //   text: "Pending",
        //   value: "pending_amount",
        //   class: "light-blue darken-3 white--text"
        // },
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
          text: "User",
          value: "user_name",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Action",
          value: "action",
          sortable: false,
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
    };
  },
  watch: {
    paid(paid) {
      if (paid == "paid") {
        // this.delivered = false;
        let selected = this.selected;

        if (selected.length > 0) {
          this.paid = false;
          this.setPaid();
        } else {
          this.snackbar = true;
          this.text = "Please select bilties first.";
        }
      }
    }
  },
  computed: {
    selected_total_amount() {
      let total_amount = 0;
      for (let item of this.selected) {
        total_amount += parseFloat(item.account_receivable);
      }
      return total_amount;
    }
  },
  created() {
    this.getLedgers();
    this.listen();
  },
  methods: {
    changeDateFormat(date) {
      let dateSplit = date.split("-");
      return dateSplit[2] + "-" + dateSplit[1] + "-" + dateSplit[0];
    },
    listen() {
      Echo.channel("ledgers").listen("LedgerAdded", ledgers => {
        this.addLedgerData(ledgers.ledgers);
        this.snackbar = true;
        this.text = "New data added";
      });
    },
    setPaid() {
      let ledgers_id = [];
      for (let item of this.selected) {
        if (item.status != "pending") {
          this.snackbar = true;
          this.text = "Can only receive payments of unpaid bilties.";
          return;
        }
        ledgers_id.push(item.id);
      }
      this.$http({
        url: `ledger/payment/${"cleared"}`,
        data: ledgers_id,
        method: "POST"
      }).then(
        res => {
          this.getLedgers();
          this.selected = [];
          this.snackbar = true;
          this.text = "successfully changed status to paid";
          return;
        },
        err => {
          this.snackbar = true;
          this.text = "Error: " + err.response.statusText;
        }
      );
    },
    getLedgers() {
      this.loading = true;
      this.$http({
        url: `ledger`,
        method: "GET"
      }).then(
        res => {
          this.addLedgerData(res.data);
          this.loading = false;
        },
        () => {}
      );
    },
    addLedgerData(data) {
      let ledgers = [];
      for (let ledger of data) {
        let ledger_data = {
          id: ledger.id,
          no: ledger.attributes.ledger_no,
          description: ledger.attributes.description,
          income_tax: ledger.attributes.income_tax,
          sales_tax: ledger.attributes.sales_tax,
          gross_total: ledger.attributes.bilty_total,
          bilty_total: ledger.attributes.bilty_total,
          account_receivable: ledger.attributes.account_receivable,
          amount_paid: ledger.attributes.amount_paid,
          pending_amount: ledger.attributes.pending_amount,
          status: ledger.attributes.status,
          user_name: ledger.relationships.user.data.user_name,
          created_at: this.changeDateFormat(
            ledger.attributes.created_at.slice(0, 10)
          ),
          created: this.changeDateFormat(
            ledger.attributes.created_at.slice(0, 10)
          ),
          bilties: []
        };
        if (ledger.relationships.customer.data) {
          this.getCustomer(ledger.relationships.customer.data.id).then(res => {
            ledger_data.customer = res;
          });
        }
        for (let bilty of ledger.relationships.bilties.data) {
          this.getBilty(bilty.id).then(res => {
            ledger_data.bilties.push(res);
          });
        }
        ledgers.push(ledger_data);
      }
      this.ledgers = ledgers;
    },
    async getCustomer(id) {
      let customer = {};
      await this.$http({
        url: `customer/${id}`,
        method: "GET"
      }).then(
        res => {
          customer = {
            customer_no: res.data.attributes.customer_no,
            name: res.data.attributes.name,
            company: res.data.attributes.company,
            cellNo: res.data.attributes.cell_no
          };
        },
        () => {}
      );
      return customer;
    },
    async getBilty(id) {
      let bilty = {};
      await this.$http({
        url: `bilty/${id}`,
        method: "GET"
      }).then(
        res => {
          bilty = {
            no: res.data.attributes.bilty_no,
            bilty_no: res.data.attributes.bilty_no,
            manual: res.data.attributes.manual,
            sender: res.data.attributes.sender,
            receiver: res.data.attributes.receiver,
            status: res.data.attributes.status,
            description: res.data.attributes.description,
            route: res.data.attributes.from + "->" + res.data.attributes.to,
            total_amount: res.data.attributes.bilty_total,
            created_at: res.data.attributes.created_at.slice(0, 10),
            rent: res.data.attributes.bilty_total,
            weight: 0,
            date: res.data.attributes.created_at.slice(0, 10)
          };
          for (let pck of res.data.relationships.packages.data) {
            this.getPackage(pck.id).then(res => {
              bilty.weight += parseFloat(res.total_weight);
            });
          }
        },
        () => {}
      );
      return bilty;
    },
    async getPackage(id) {
      let pck = {};
      await this.$http({
        url: `package/${id}`,
        method: "GET"
      }).then(
        res => {
          pck = {
            total_weight: res.data.attributes.total_weight
          };
        },
        () => {}
      );
      return pck;
    },
    editItem(item) {
      if (item.status == "pending") {
        let user = this.$auth.user();
        if (user.role == 2) {
          this.$router.push({ path: `/admin/ledger/edit/${item.id}` });
        } else {
          this.$router.push({ path: `/operator/ledger/edit/${item.id}` });
        }
      } else {
        this.snackbar = true;
        this.text = "Ledger is locked";
      }
    },
    printItem(item) {
      let taxed_on = "None";
      if (item.income_tax > parseFloat(0) && item.sales_tax > parseFloat(0)) {
        taxed_on = "Both";
      } else if (item.sales_tax > parseFloat(0)) {
        taxed_on = "Sales Tax";
      } else if (item.income_tax > parseFloat(0)) {
        taxed_on = "Income Tax";
      }
      item.taxed_on = taxed_on;
      item.invoice_no = item.no;
      item.customer_data = item.customer;
      item.selected_items = item.bilties;

      this.$store.dispatch("destroyInvoice");
      this.$store.dispatch("createInvoice", item);
      let routeData = this.$router.resolve({
        name: "invoice",
        query: { id: item.invoice_no }
      });
      window.open(routeData.href, "_blank");
    }
  }
};
</script>