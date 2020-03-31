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
      <v-card-title class="px-8 pt-8 headline">ADD CUSTOMER FORM</v-card-title>
      <v-card-text>
        <v-form v-model="valid" ref="form">
          <v-container>
            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.customerNo"
                  label="Customer No."
                  filled
                  dense
                  disabled
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.customerName"
                  :rules="nameRule"
                  filled
                  dense
                  label="Name"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.company"
                  :rules="selectRule"
                  filled
                  dense
                  label="Company"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field v-model="customer.email" filled dense label="Email"></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.perKg"
                  :rules="numberRule"
                  filled
                  dense
                  label="Per Kg"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.perCbm"
                  :rules="numberRule"
                  filled
                  dense
                  label="Per Cbm"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.perPckg"
                  :rules="numberRule"
                  filled
                  dense
                  label="Per Package"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  v-model="customer.cellNo"
                  :rules="selectRule"
                  filled
                  dense
                  label="Cell No"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="customer.sender"
                  :rules="nameRule"
                  filled
                  dense
                  label="Sender Name"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="8">
                <v-text-field
                  v-model="customer.sender_address"
                  :rules="descriptionRule"
                  filled
                  dense
                  label="Sender Address"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="customer.receiver"
                  :rules="nameRule"
                  filled
                  dense
                  label="Receiver Name"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="8">
                <v-text-field
                  v-model="customer.receiver_address"
                  :rules="descriptionRule"
                  filled
                  dense
                  label="Receiver Address"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="customer.incomeTax"
                  :rules="numberRule"
                  filled
                  dense
                  label="Income Tax"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="customer.salesTax"
                  :rules="numberRule"
                  filled
                  dense
                  label="Sales Tax"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row justify="end">
              <v-btn class="px-8 mx-4" depressed color="primary" @click="save">Save</v-btn>
            </v-row>
            <v-snackbar v-model="snackbar">
              {{ text }}
              <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
            </v-snackbar>
          </v-container>
        </v-form>
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
          text: "Customer"
        },
        {
          text: "Add Customer"
        }
      ],
      isUpdating: false,
      snackbar: false,
      text: "",
      customer: {
        customerName: "",
        cellNo: "",
        customerNo: "",
        perKg: "",
        perCbm: "",
        perPckg: "",
        incomeTax: "",
        salesTax: "",
        company: "",
        email: "",
        sender: "",
        receiver: "",
        sender_address: "",
        receiver_address: ""
      },
      valid: true,

      nameRule: [
        v => !!v || "Field is required",
        v => (v && v.length <= 30) || "Field must be less than 30 characters",
        v =>
          /(?=.*[A-Z])/.test(v) ||
          /(?=.*[a-z])/.test(v) ||
          "Only characters allowed"
      ],
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
  created() {
    this.getLastCustomerNo();
  },
  mounted() {
    Echo.channel("customers").listen("CustomerAdded", customers => {
      console.log(customers);
    });
  },
  methods: {
    getLastCustomerNo() {
      this.$http({
        url: `customer/last`,
        method: "GET"
      }).then(
        res => {
          this.customer.customerNo = parseInt(res.data);
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching customer number, please try again";
        }
      );
    },
    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    save() {
      if (this.$refs.form.validate()) {
        this.$http({
          url: `customer/create`,
          data: this.customer,
          method: "POST"
        }).then(
          res => {
            let customer = {
              customerName: "",
              cellNo: "",
              customerNo: "",
              perKg: "",
              perCbm: "",
              perPckg: "",
              company: "",
              email: "",
              sender: "",
              receiver: "",
              sender_address: "",
              receiver_address: ""
            };
            this.customer = customer;
            this.resetValidation();
            this.getLastCustomerNo();
            this.snackbar = true;
            this.text = "Success adding customer";
          },
          () => {
            this.snackbar = true;
            this.text = "Error adding customer";
          }
        );
      }
    }
  }
};
</script>