<template>
  <v-form v-model="valid">
    <v-container>
      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.customerNo" label="Customer No." disabled></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.customerName" :rules="nameRules" label="Name" required></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.cellNo" :rules="nameRules" label="Cell No" required></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.perKg" :rules="nameRules" label="Per Kg" required></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.perCbm" :rules="nameRules" label="Per Cbm" required></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="customer.perPckg" :rules="nameRules" label="Per Package" required></v-text-field>
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
    </v-container>
  </v-form>
</template>

<script>
export default {
  data() {
    return {
      customer: {
        customerName: "",
        cellNo: "",
        customerNo: "",
        perKg: "",
        perCbm: "",
        perPckg: ""
      }
    };
  },
  created() {
    this.$http({
      url: `customer/last`,
      method: "GET"
    }).then(
      res => {
        this.customer.customerNo = parseInt(res.data);
        console.log(res.data);
      },
      () => {
        console.log("error occured");
        // this.has_error = true
      }
    );
  },
  methods: {
    save() {
      this.$http({
        url: `customer/create`,
        data: this.customer,
        method: "POST"
      }).then(
        res => {
          console.log(res);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    }
  }
};
</script>