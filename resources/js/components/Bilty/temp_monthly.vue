<template>
  <v-container>
    <v-row align="center">
      <v-col cols="12" md="6">
        <v-autocomplete
          v-model="customer"
          :disabled="isUpdating"
          :items="customers"
          chips
          item-text="name"
          item-value="id"
          label="Search Customer"
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
      <v-col cols="12" md="3">
        <v-btn @click="search_customer">Search</v-btn>
      </v-col>
    </v-row>
    <v-divider horizontal dark></v-divider>
    <v-row>
      <v-col cols="12" sm="4">
        <v-text-field
          label="Income Tax"
          placeholder="Income Tax"
          v-model="income_tax"
          outlined
          dense
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="4">
        <v-text-field label="Sales Tax" placeholder="Sales Tax" v-model="sales_tax" outlined dense></v-text-field>
      </v-col>
      <v-col class="d-flex" cols="12" sm="4">
        <v-select :items="apply_tax_on" v-model="taxed_on" label="Apply" outlined dense></v-select>
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
              <v-col cols="12" md="6">
                <v-text-field
                  label="From"
                  placeholder="From"
                  v-model="item.from"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="To" placeholder="To" v-model="item.to" readonly outlined dense></v-text-field>
              </v-col>
            </v-row>
            <v-row class="mt-0">
              <v-col cols="12" class="pt-sm-0">
                <v-textarea
                  label="Receiver Address"
                  auto-grow
                  outlined
                  readonly
                  v-model="item.receiver_address"
                  rows="1"
                  row-height="10"
                ></v-textarea>
              </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
              <v-subheader>Package Details</v-subheader>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-data-table
                  :headers="headers_packages"
                  :items="item.packages"
                  :items-per-page="5"
                ></v-data-table>
              </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
              <v-subheader>Account Receivable</v-subheader>
            </v-row>
            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                  label="Bilty Charges"
                  placeholder="Bilty Charges"
                  v-model="item.bilty_charges"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  label="Local Charges"
                  placeholder="Local Charges"
                  v-model="item.local_charges"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  label="Packages Total"
                  placeholder="Packages Total"
                  v-model="item.package_total"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  label="Total Amount"
                  placeholder="Total Amount"
                  v-model="item.total_amount"
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
    <v-dialog v-model="paid" scrollable light max-width="600px">
      <template v-slot:activator="{ on }">
        <v-btn class="my-4 mx-4 float-right" color="primary" v-on="on">Paid</v-btn>
      </template>
      <v-card class="blue lighten-5">
        <v-card-title>Status of following bilties will be changed to Delivered</v-card-title>
        <v-divider></v-divider>
        <v-card-text style="height: 300px;">
          <v-row style="height: 230px;">
            <div v-if="selected.length > 0">
              <v-list-item v-for="item in selected" :key="item.id">
                <v-list-item-content>
                  <v-list-item-title>
                    <v-icon>mdi-check-bold</v-icon>
                    {{item.no}} | {{item.created_at}} | {{item.total_amount}}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </div>
            <div v-else class="mt-4 subtitle-1 text-center" align="center">No Items selected</div>
          </v-row>
          <v-row justify="end" align="end">
            <v-subheader class="title subtitle-1">Rs.{{selected_gross_amount}}</v-subheader>
          </v-row>
          <v-row v-if="taxed_on === 'Income Tax' || taxed_on === 'Both'" justify="end" align="end">
            <v-subheader class="title subtitle-2">Rs.{{selected_income_tax_amount}}</v-subheader>
          </v-row>
          <v-row v-if="taxed_on === 'Sales Tax' || taxed_on === 'Both'" justify="end" align="end">
            <v-subheader class="title subtitle-2">Rs.{{selected_sales_tax_amount}}</v-subheader>
          </v-row>
          <v-row justify="end" align="end">
            <v-subheader class="title subtitle-1">Rs.{{selected_net_total}}</v-subheader>
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
    <v-snackbar v-model="snackbar">
      {{ text }}
      <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </v-container>
</template>


<script>
export default {
  data() {
    return {
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
      expanded: [],
      selected: [],
      loading: false,
      singleExpand: false,
      headers_packages: [
        { text: "Description", align: "left", value: "description" },
        { text: "Unit", value: "unit" },
        { text: "Quantity", value: "quantity" },
        { text: "Weight", value: "total_weight" },
        { text: "Volume", value: "total_volume" },
        { text: "Labour", value: "labour" },
        { text: "Rate", value: "rate" },
        { text: "Rent", value: "rent" }
      ],
      headers: [
        {
          text: "Builty No",
          align: "left",
          value: "no"
        },
        { text: "Manual", value: "manual" },
        { text: "Lc/bl-no", value: "lc_bl_no" },
        { text: "Sender", value: "sender" },
        { text: "Receiver", value: "receiver" },
        { text: "Status", value: "status" },
        { text: "Payment", value: "payment_status" },
        { text: "Amount", value: "total_amount" },
        { text: "Created", value: "created_at" },
        { text: "", value: "data-table-expand" }
      ],
      bilties: [],
      packages: []
    };
  },
  computed: {
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
  },
  methods: {
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
            console.log(res);
            let bilties = [];
            for (let bilty of res.data) {
              let bilty_data = {
                id: bilty.id,
                no: bilty.attributes.bilty_no,
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
                packages: [],
                package_total: bilty.attributes.packages_total,
                total_amount: bilty.attributes.bilty_total
              };
              if (bilty.relationships.customer.data) {
                this.getCustomer(bilty.relationships.customer.data.id).then(
                  res => {
                    bilty_data.customer = res;
                  }
                );
              }
              for (let pck of bilty.relationships.packages.data) {
                console.log(pck.id);
                this.getPackage(pck.id).then(res => {
                  bilty_data.packages.push(res);
                });
              }
              bilties.push(bilty_data);
            }
            this.bilties = bilties;
            console.log(this.bilties);
          },
          () => {
            console.log("error occured");
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
          console.log(res);
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
        () => {}
      );
    },
    setPaid() {
      let bilties_id = [];
      for (let item of this.selected) {
        bilties_id.push(item.id);
      }
      this.$http({
        url: `bilties/payment/${"paid"}`,
        data: bilties_id,
        method: "POST"
      }).then(
        res => {
          this.customer = "";
          this.search_customer();
          this.snackbar = true;
          this.text = "successfully changed status to paid";
        },
        err => {
          this.snackbar = true;
          this.text = "Error: " + err.response.statusText;
        }
      );
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
            company: res.data.attributes.company
          };
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
      // console.log(customer);
      return customer;
    },
    async getPackage(id) {
      let pck = {};
      await this.$http({
        url: `package/${id}`,
        method: "GET"
      }).then(
        res => {
          pck = {
            description: res.data.attributes.description,
            unit: res.data.attributes.unit,
            quantity: res.data.attributes.quantity,
            total_weight: res.data.attributes.total_weight,
            total_volume: res.data.attributes.total_volume,
            labour: res.data.attributes.labour,
            rate: res.data.attributes.rate,
            labour: res.data.attributes.labour,
            rent: res.data.attributes.rent
          };
          console.log(pck);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
      // console.log(customer);
      return pck;
    },
    editItem(item) {
      let user = this.$auth.user();
      if (user.role == 2) {
        this.$router.push({ path: `/admin/bilty/edit/${item.id}` });
      } else {
        this.$router.push({ path: `/operator/bilty/edit/${item.id}` });
      }
    }
  }
};
</script>