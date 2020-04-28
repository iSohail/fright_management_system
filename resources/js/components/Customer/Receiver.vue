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
      <v-card-title class="px-8 pt-8 headline">Receiver</v-card-title>
      <v-card-text>
        <v-form v-model="valid" ref="form">
          <v-container>
            <v-card class="mx-auto pa-8" style="max-width: 600px ;height: 100%;">
              <v-card-title class="mx-auto">Add New Receiver</v-card-title>
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
            Receivers
            <v-spacer></v-spacer>
            <v-text-field
              @keyup.native.enter="searchReceiver"
              v-model="search"
              append-icon="mdi-magnify"
              append-outer-icon="mdi-lock-reset"
              clear-icon="mdi-close-circle"
              clearable
              @click:append="searchReceiver"
              @click:append-outer="defaultReceiver"
              @click:clear="defaultReceiver"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="receivers"
            :options.sync="options"
            :server-items-length="totalReceivers"
            :footer-props="{
              itemsPerPageOptions: [5, 10, 15],
            }"
            :loading="loading"
            loading-text="Loading... Please wait"
            item-key="id"
          >
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
        text: "Customer/Receiver"
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
    receivers: [],
    loading: false,
    totalReceivers: 0,
    options: {},
    headers: [
      {
        text: "Customer No",
        align: "start",
        value: "customer_no",
        sortable: false,
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
        sortable: false,
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
  watch: {
    // watch for any changes in table options and trigger function
    options: {
      handler() {
        this.getReceiversServerSide().then(data => {
          this.receivers = data.items;
          this.totalReceivers = data.total;
        });
      },
      deep: true
    }
  },
  mounted() {
    this.getReceiversServerSide().then(data => {
      this.receivers = data.items;
      this.totalReceivers = data.total;
    });
    this.getCustomers();
  },
  methods: {
    // clearing up search
    defaultReceiver() {
      this.search = "";
      this.getReceiversServerSide().then(data => {
        this.receivers = data.items;
        this.totalReceivers = data.total;
      });
    },
    // if search button is clicked or input given
    searchReceiver() {
      this.getReceiversServerSide().then(data => {
        this.receivers = data.items;
        this.totalReceivers = data.total;
      });
    },
    // fetch receivers from backend
    getReceiversServerSide() {
      this.loading = true;
      // using async function to wait for the functions to execute and release results
      return new Promise(async (resolve, reject) => {
        const { sortBy, sortDesc, page, itemsPerPage } = this.options;
        var items = [];
        var total = 0;
        var flag = false;

        // if search is given fetch searched item
        // else if check if sortBy and descending is given fetch items orderBy
        // else fetch all receivers by Latest
        if (this.search) {
          // searching
          await this.getReceiversFiltered(page, itemsPerPage, this.search).then(
            data => {
              items = data.data;
              total = data.total_items;
              flag = true;
            }
          );
        } else if (sortBy.length === 1 && sortDesc.length === 1) {
          // sorting
          await this.getReceiversSorted(
            page,
            itemsPerPage,
            sortBy[0],
            sortDesc[0]
          ).then(data => {
            items = data.data;
            total = data.total_items;
            flag = true;
          });
        } else {
          // fetch by latest
          await this.getReceivers(page, itemsPerPage).then(data => {
            items = data.data;
            total = data.total_items;
            flag = true;
          });
        }

        // check if functions are executed and release variables
        if (flag == true) {
          this.loading = false;
          resolve({
            items,
            total
          });
        }
      });
    },
    async getReceivers(page, items_per_page) {
      let receivers = {};
      await this.$http({
        url: `receivers/paginate?page=${page}&per_page=${items_per_page}`,
        method: "GET"
      }).then(
        res => {
          receivers.data = this.addReceiverData(res.data.data);
          receivers.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching receivers, please refresh";
        }
      );
      return receivers;
    },
    async getReceiversFiltered(page, items_per_page, search) {
      let receivers = {};
      await this.$http({
        url: `receivers/search?page=${page}&per_page=${items_per_page}&query=${search}`,
        method: "GET"
      }).then(
        res => {
          receivers.data = this.addReceiverData(res.data.data);
          receivers.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching receivers, please refresh";
        }
      );
      return receivers;
    },
    async getReceiversSorted(page, items_per_page, sortBy, sortDesc) {
      let receivers = {};
      await this.$http({
        url: `receivers/sort?page=${page}&per_page=${items_per_page}&sort_by=${sortBy}&sort_desc=${sortDesc}`,
        method: "GET"
      }).then(
        res => {
          receivers.data = this.addReceiverData(res.data.data);
          receivers.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching receivers, please refresh";
        }
      );
      return receivers;
    },
    addReceiverData(data) {
      let receivers_arr = [];
      for (let receiver of data) {
        let receivers_data = {
          id: receiver.id,
          name: receiver.attributes.name,
          address: receiver.attributes.address,
          customer_no: receiver.relationships.customer.data.customer_no
        };
        receivers_arr.push(receivers_data);
      }
      return receivers_arr;
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
        url: `receiver/${item.id}`,
        method: "DELETE"
      }).then(
        res => {
          this.getReceiversServerSide().then(data => {
            this.receivers = data.items;
            this.totalReceivers = data.total;
          });
          this.snackbar = true;
          this.text = "Success deleting receiver";
        },
        err => {
          this.snackbar = true;
          this.text = "Error deleting receiver";
        }
      );
    },

    save() {
      if (this.$refs.form.validate()) {
        let receiver = {
          customer_id: this.customer,
          name: this.name,
          address: this.address
        };
        this.$http({
          url: `receiver/create`,
          data: receiver,
          method: "POST"
        }).then(
          res => {
            this.name = "";
            this.address = "";
            this.customer = "";
            this.$refs.form.resetValidation();
            this.getReceiversServerSide().then(data => {
              this.receivers = data.items;
              this.totalReceivers = data.total;
            });
            this.snackbar = true;
            this.text = "Success adding receiver";
          },
          err => {
            this.snackbar = true;
            this.text = "Error adding receiver";
          }
        );
      }
    }
  }
};
</script>
