<template>
  <div>
    <v-alert color="light-blue darken-3 mb-0" dark dense tile flat>
      <v-breadcrumbs class="py-3" dark :items="bread_crumb_items">
        <template v-slot:item="{ item }">
          <v-breadcrumbs-item>{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
        </template>
      </v-breadcrumbs>
    </v-alert>

    <v-card
      style="height: 100%; min-height: 100vh"
      :loading="isUpdating"
      dark
      flat
      tile
      :disabled="isUpdating"
    >
      <template v-slot:progress>
        <v-progress-linear absolute color="blue lighten-3" height="4" indeterminate></v-progress-linear>
      </template>
      <v-card-title class="px-8 pt-8 headline">EDIT LEDGER FORM</v-card-title>
      <v-card-text>
        <v-container>
          <v-form ref="ledger_form">
            <v-row align="center">
              <v-col cols="12" sm="4">
                <v-text-field disabled v-model="invoice_no" label="Invoice Number"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-autocomplete
                  v-model="customer"
                  :disabled="isUpdating"
                  :items="customers"
                  chips
                  :rules="selectRule"
                  item-text="name"
                  item-value="id"
                  label="Search Customer"
                  dense
                  filled
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
              <v-col cols="12" sm="2">
                <v-btn @click="search_customer">Search</v-btn>
              </v-col>
            </v-row>
            <v-divider horizontal dark></v-divider>
            <v-row>
              <v-col cols="12" md="12">
                <v-textarea
                  label="Description"
                  rows="1"
                  row-height="20"
                  v-model="description"
                  :rules="descriptionRule"
                  class="ma-1"
                  dense
                  filled
                ></v-textarea>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="4">
                <v-text-field
                  label="Income Tax"
                  placeholder="Income Tax"
                  v-model="income_tax"
                  :rules="numberRule"
                  outlined
                  dense
                  filled
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="4">
                <v-text-field
                  label="Sales Tax"
                  placeholder="Sales Tax"
                  v-model="sales_tax"
                  :rules="numberRule"
                  outlined
                  dense
                  filled
                ></v-text-field>
              </v-col>
              <v-col class="d-flex" cols="12" sm="4">
                <v-select
                  :items="apply_tax_on"
                  v-model="taxed_on"
                  label="Apply"
                  outlined
                  dense
                  filled
                ></v-select>
              </v-col>
            </v-row>
            <v-card>
              <v-card-title>
                Monthly Customers
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
                :items="bilties"
                :search="search"
                show-select
                item-key="id"
                :loading="loading"
                loading-text="Loading... Please wait"
              ></v-data-table>
            </v-card>

            <v-card-actions>
              <v-row justify="end">
                <v-dialog v-model="paid" persistent max-width="290">
                  <template v-slot:activator="{ on }">
                    <v-btn color="primary float-right my-4" dark v-on="on">Update</v-btn>
                  </template>
                  <v-card>
                    <v-card-title class="headline">Generate Invoice</v-card-title>
                    <v-card-text>Are you sure you want to generate Invoice?</v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="green darken-1" text @click="paid = false">No</v-btn>
                      <v-btn color="green darken-1" text @click="paid = 'paid'">Yes</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-row>
            </v-card-actions>
            <v-snackbar v-model="snackbar">
              {{ text }}
              <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
            </v-snackbar>
          </v-form>
        </v-container>
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
          text: "Add Ledger"
        }
      ],
      isUpdating: false,
      invoice_no: "",
      description: "",
      taxed_on: "None",
      income_tax: "0",
      sales_tax: "0",
      apply_tax_on: ["None", "Income Tax", "Sales Tax", "Both"],
      customer: "",
      isUpdating: false,
      customers: [],
      snackbar: false,
      text: "",
      paid: false,
      search: "",
      selected: [],
      selected_items: [],
      loading: false,
      headers: [
        {
          text: "",
          value: "data-table-select",
          class: "light-blue darken-3 white--text"
        },
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
          text: "Lc/bl-no",
          value: "lc_bl_no",
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
          text: "Payment",
          value: "payment_status",
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
        },
        {
          text: "",
          value: "data-table-expand",
          class: "light-blue darken-3 white--text"
        }
      ],
      bilties: [],
      selectRule: [v => !!v || "Field is required"],
      descriptionRule: [
        v => !!v || "Description is required",
        v =>
          (v && v.length <= 1000) ||
          "Descriptipn must be less than 1000 characters"
      ],
      numberRule: [
        v => !!v || "Field is required",
        v => {
          if (!isNaN(parseFloat(v)) && v >= 0 && v <= 9999999) return true;
          return "Only numbers allowed";
        }
      ]
    };
  },
  computed: {
    getCurrentDate() {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, "0");
      var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
      var yyyy = today.getFullYear();

      today = mm + "/" + dd + "/" + yyyy;
      return today;
    },
    selected_gross_amount() {
      let total_amount = 0;
      for (let item of this.selected) {
        total_amount += parseFloat(item.total_amount);
      }
      return total_amount;
    },
    selected_income_tax_amount() {
      let income_tax_amount = 0;
      if (this.taxed_on == "Income Tax" || this.taxed_on == "Both") {
        income_tax_amount =
          (parseFloat(this.income_tax) *
            parseFloat(this.selected_gross_amount)) /
          100;
      }
      return income_tax_amount.toFixed(2);
    },
    selected_sales_tax_amount() {
      let sales_tax_amount = 0;
      if (this.taxed_on == "Sales Tax" || this.taxed_on == "Both") {
        sales_tax_amount =
          (parseFloat(this.sales_tax) *
            parseFloat(this.selected_gross_amount)) /
          100;
      }
      return sales_tax_amount.toFixed(2);
    },
    selected_net_total() {
      return (
        parseFloat(this.selected_gross_amount) +
        parseFloat(this.selected_sales_tax_amount) -
        parseFloat(this.selected_income_tax_amount)
      ).toFixed(2);
    }
  },
  watch: {
    customer() {
      if (!this.customer) {
        this.bilties = [];
      }
    },
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
  created() {
    this.getCustomers();
    this.getLedger(this.$route.params.id);
  },
  methods: {
    async getLedger(id) {
      await this.$http({
        url: `ledger/${id}`,
        method: "GET"
      }).then(
        res => {
          this.customer = res.data.relationships.customer.data.id;
          this.invoice_no = res.data.attributes.ledger_no;
          this.description = res.data.attributes.description;
          this.search_customer();
          for (let bilty of res.data.relationships.bilties.data) {
            this.getBilty(bilty.id);
          }
        },
        () => {
          this.isUpdating = false;
          this.snackbar = true;
          this.text = "Can not fetch challan details";
        }
      );
    },
    getBilty(id) {
      this.$http({
        url: `bilty/${id}`,
        method: "GET"
      }).then(
        res => {
          let bilty = res.data;
          let bilty_data = {
            id: bilty.id,
            no: bilty.attributes.bilty_no,
            description: bilty.attributes.description,
            lc_bl_no: bilty.attributes.lg_bl_no,
            manual: bilty.attributes.manual,
            from: bilty.attributes.from,
            to: bilty.attributes.to,
            sender: bilty.attributes.sender,
            receiver: bilty.attributes.receiver,
            receiver_address: bilty.attributes.receiver_address,
            status: bilty.attributes.status,
            payment_status: bilty.attributes.payment_status,
            created_at: bilty.attributes.created_at,
            bilty_charges: bilty.attributes.bilty_charges,
            local_charges: bilty.attributes.local_charges,
            package_total: bilty.attributes.packages_total,
            total_amount: bilty.attributes.bilty_total
          };
          this.bilties.push(bilty_data);
          this.selected.push(bilty_data);
        },
        () => {
          this.snackbar = true;
          this.text = "Error!";
        }
      );
    },
    search_customer() {
      if (this.customer) {
        let customer_data = this.customers.find(k => k.id == this.customer);
        this.income_tax = customer_data.incomeTax;
        this.sales_tax = customer_data.salesTax;
        this.$http({
          url: `bilties/customer/${this.customer}`,
          method: "GET"
        }).then(
          res => {
            let bilties = [];
            for (let bilty of res.data) {
              let bilty_data = {
                id: bilty.id,
                no: bilty.attributes.bilty_no,
                description: bilty.attributes.description,
                lc_bl_no: bilty.attributes.lg_bl_no,
                manual: bilty.attributes.manual,
                from: bilty.attributes.from,
                to: bilty.attributes.to,
                sender: bilty.attributes.sender,
                receiver: bilty.attributes.receiver,
                receiver_address: bilty.attributes.receiver_address,
                status: bilty.attributes.status,
                payment_status: bilty.attributes.payment_status,
                created_at: bilty.attributes.created_at,
                bilty_charges: bilty.attributes.bilty_charges,
                local_charges: bilty.attributes.local_charges,
                package_total: bilty.attributes.packages_total,
                total_amount: bilty.attributes.bilty_total
              };
              bilties.push(bilty_data);
            }
            this.bilties = bilties;
          },
          () => {
            this.snackbar = true;
            this.text = "Error!";
          }
        );
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
              perPackage: customer.attributes.per_pck_rate,
              incomeTax: customer.attributes.income_tax,
              salesTax: customer.attributes.sales_tax
            });
          }
          this.customers = customers;
        },
        () => {
          this.snackbar = true;
          this.text = "Error!";
        }
      );
    },
    setPaid() {
      if (this.$refs.ledger_form.validate()) {
        let check = true;
        let ledger_data = {
          description: this.description,
          income_tax: this.selected_income_tax_amount,
          sales_tax: this.selected_sales_tax_amount,
          bilty_total: this.selected_gross_amount,
          account_receivable: this.selected_net_total,
          amount_paid: 0,
          pending_amount: this.selected_net_total,
          status: "pending",
          customer_id: this.customer,
          bilties: []
        };
        let bilties_id = [];
        for (let item of this.selected) {
          bilties_id.push(item.id);
        }
        ledger_data.bilties = bilties_id;
        this.$http({
          url: `ledger/${this.$route.params.id}`,
          data: ledger_data,
          method: "PUT"
        }).then(
          res => {
            this.snackbar = true;
            this.text = "Successfully updated entry in ledger";
            let user = this.$auth.user();
            if (user.role == 2) {
              this.$router.push({ path: `/admin/ledger/manage` });
            } else {
              this.$router.push({ path: `/operator/ledger/manage` });
            }
          },
          err => {
            this.snackbar = true;
            this.text = "Error: " + err.response.statusText;
            check = false;
          }
        );
      }
    }
  }
};
</script>
