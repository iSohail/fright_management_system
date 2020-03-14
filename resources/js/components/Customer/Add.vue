<template>
  <v-form v-model="valid" ref="form">
    <v-container>
      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.customerNo" label="Customer No." disabled></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.customerName" :rules="nameRule" label="Name" required></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.company" :rules="nameRule" label="Company" required></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="3">
          <v-text-field v-model="customer.perKg" :rules="numberRule" label="Per Kg" required></v-text-field>
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field v-model="customer.perCbm" :rules="numberRule" label="Per Cbm" required></v-text-field>
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field v-model="customer.perPckg" :rules="numberRule" label="Per Package" required></v-text-field>
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field v-model="customer.cellNo" :rules="stringRule" label="Cell No" required></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.sender" :rules="nameRule" label="Sender Name" required></v-text-field>
        </v-col>
        <v-col cols="12" md="8">
          <v-text-field
            v-model="customer.sender_address"
            :rules="descriptionRule"
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
            label="Receiver Name"
            required
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="8">
          <v-text-field
            v-model="customer.receiver_address"
            :rules="descriptionRule"
            label="Receiver Address"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4"></v-col>
        <v-col cols="12" md="4"></v-col>
        <v-col class="text-center" cols="12" md="4">
          <div class="pl-12">
            <v-btn depressed color="primary" @click="save">Save</v-btn>
          </div>
        </v-col>
      </v-row>
      <v-snackbar v-model="snackbar">
        {{ text }}
        <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-container>
  </v-form>
</template>

<script>
export default {
  data() {
    return {
      snackbar: false,
      text: "",
      customer: {
        customerName: "",
        cellNo: "",
        customerNo: "",
        perKg: "",
        perCbm: "",
        perPckg: "",
        company: "",
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
      stringRule: [
        v => !!v || "Field is required",
        v => (v && v.length <= 15) || "Field must be less than 15 characters"
      ],
      descriptionRule: [
        v => !!v || "Description is required",
        v =>
          (v && v.length <= 1000) ||
          "Descriptipn must be less than 1000 characters"
      ],
      numberRule: [
        v => !!v || "Field is required",
        v => {
          if (!v.trim) return true;
          if (!isNaN(parseFloat(v)) && v >= 0 && v <= 99999999) return true;
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