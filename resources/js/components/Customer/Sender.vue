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
      <v-card-title class="px-8 pt-8 headline">Sender</v-card-title>
      <v-card-text>
        <v-form v-model="valid" ref="form">
          <v-container>
            <v-card class="mx-auto pa-8" style="max-width: 600px ;height: 100%;">
              <v-card-title class="mx-auto">Add New Sender</v-card-title>
              <v-row>
                <v-col cols="12">
                  <v-autocomplete
                    v-model="customer"
                    :items="customers"
                    :rules="selectRule"
                    filled
                    chips
                    dense
                    item-text="name"
                    item-value="id"
                    label="Customer"
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
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field v-model="name" :rules="nameRule" label="Name" filled dense></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-textarea
                    filled
                    dense
                    auto-grow
                    v-model="address"
                    :rules="selectRule"
                    label="Address"
                    rows="2"
                    row-height="20"
                  ></v-textarea>
                </v-col>
              </v-row>

              <v-row justify="end">
                <v-btn class="px-8 mx-4" depressed color="primary" @click="save">Save</v-btn>
              </v-row>
              <v-snackbar v-model="snackbar">
                {{ text }}
                <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
              </v-snackbar>
            </v-card>
          </v-container>
        </v-form>
        <v-card>
          <v-card-title>
            Senders
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table :headers="headers" :items="senders" :search="search">
            <template v-slot:item.action="{ item }">
              <v-row>
                <v-col cols="6" class="px-0 text-right">
                  <v-btn class="red mr-sm-3" small @click="deleteItem(item)">
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </template>
          </v-data-table>
        </v-card>
      </v-card-text>

      <v-snackbar v-model="snackbar">
        {{ text }}
        <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    bread_crumb_items: [
      {
        text: "Customer/Sender"
      }
    ],
    name: "",
    address: "",
    customer: "",
    search: "",
    snackbar: false,
    isUpdating: false,
    valid: true,
    text: "",
    customers: [],
    senders: [],
    headers: [
      {
        text: "Customer No",
        align: "start",
        value: "customer_no",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Name",
        value: "name",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Address",
        value: "address",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Action",
        value: "action",
        class: "light-blue darken-3 white--text"
      }
    ],
    nameRule: [
      v => !!v || "Field is required",
      v => (v && v.length <= 30) || "Field must be less than 30 characters",
      v =>
        /(?=.*[A-Z])/.test(v) ||
        /(?=.*[a-z])/.test(v) ||
        "Only characters allowed"
    ],
    selectRule: [v => !!v || "Field is required"]
  }),
  created() {
    this.getSenders();
    this.getCustomers();
  },
  methods: {
    getSenders() {
      this.$http({
        url: `sender`,
        method: "GET"
      }).then(
        res => {
          let senders_arr = [];
          for (let sender of res.data) {
            let senders_data = {
              id: sender.id,
              name: sender.attributes.name,
              address: sender.attributes.address,
              customer_no: sender.relationships.customer.data.customer_no
            };
            senders_arr.push(senders_data);
          }
          this.senders = senders_arr;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching senders, please refresh";
        }
      );
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
              company: customer.attributes.company
            });
          }
          this.customers = customers;
        },
        err => {
          this.snackbar = true;
          this.text = "Error fetching customers, please refresh";
        }
      );
    },

    deleteItem(item) {
      this.$http({
        url: `sender/${item.id}`,
        method: "DELETE"
      }).then(
        res => {
          this.getSenders();
          this.snackbar = true;
          this.text = "Success deleting sender";
        },
        err => {
          this.snackbar = true;
          this.text = "Error deleting sender";
        }
      );
    },

    save() {
      if (this.$refs.form.validate()) {
        let sender = {
          customer_id: this.customer,
          name: this.name,
          address: this.address
        };
        this.$http({
          url: `sender/create`,
          data: sender,
          method: "POST"
        }).then(
          res => {
            this.name = "";
            this.address = "";
            this.customer = "";
            this.$refs.form.resetValidation();
            this.getSenders();
            this.snackbar = true;
            this.text = "Success adding sender";
          },
          err => {
            this.snackbar = true;
            this.text = "Error adding sender";
          }
        );
      }
    }
  }
};
</script>
