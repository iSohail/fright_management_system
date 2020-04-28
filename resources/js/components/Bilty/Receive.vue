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
      <v-card-title class="px-8 pt-8 headline">RECEIVE BILTY</v-card-title>
      <v-card-text>
        <v-card class="mx-4 my-2 mb-4" id="customeSelect">
          <v-card-title>
            Bilties
            <v-spacer></v-spacer>
            <v-text-field
              @keyup.native.enter="searchBilty"
              v-model="search"
              append-icon="mdi-magnify"
              append-outer-icon="mdi-lock-reset"
              clear-icon="mdi-close-circle"
              clearable
              @click:append="searchBilty"
              @click:append-outer="defaultBilty"
              @click:clear="defaultBilty"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            v-model="selected"
            :headers="headers"
            :items="bilties"
            :options.sync="options"
            :server-items-length="totalBilties"
            :footer-props="{
              itemsPerPageOptions: [5, 10, 15],
            }"
            :loading="loading"
            loading-text="Loading... Please wait"
            item-key="id"
            show-expand
            show-select
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
                    <v-text-field
                      label="To"
                      placeholder="To"
                      v-model="item.to"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
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
      </v-card-text>
      <v-card-actions>
        <v-row justify="end" class="mx-4">
          <v-dialog v-model="delivered" scrollable light max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn class="my-4 mx-4 float-right" color="warning" v-on="on">Delivered</v-btn>
            </template>
            <v-card dark>
              <v-card-title
                class="subtitle-2"
              >Status of following bilties will be changed to Delivered</v-card-title>
              <v-divider></v-divider>
              <v-card-text style="height: 300px;">
                <div v-if="selected.length > 0">
                  <v-simple-table height="230px" dense>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left"></th>
                          <th class="text-left">Bilty No</th>
                          <th class="text-left">Created</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in selected" :key="item.id">
                          <td>
                            <v-icon>mdi-check-bold</v-icon>
                          </td>
                          <td>{{ item.no }}</td>
                          <td>{{ item.created_at }}</td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </div>
                <div v-else class="mt-4 subtitle-1 text-center" align="center">No Items selected</div>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-btn color="blue darken-1" text @click="delivered = false">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1 float-right" text @click="delivered = 'delivered'">Okay</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="paid" scrollable light max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn class="my-4 mx-4 float-right" color="primary" v-on="on">Paid</v-btn>
            </template>
            <v-card dark>
              <v-card-title
                class="subtitle-2"
              >Status of following bilties will be changed to Delivered</v-card-title>
              <v-divider></v-divider>
              <v-card-text style="height: 300px;">
                <v-row style="height: 230px; width: 100%">
                  <div style="width: 100%" v-if="selected.length > 0">
                    <v-simple-table height="230px" dense>
                      <template v-slot:default>
                        <thead>
                          <tr>
                            <th class="text-left"></th>
                            <th class="text-left">Bilty No</th>
                            <th class="text-left">Created</th>
                            <th class="text-left">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in selected" :key="item.id">
                            <td>
                              <v-icon>mdi-check-bold</v-icon>
                            </td>
                            <td>{{ item.no }}</td>
                            <td>{{ item.created_at }}</td>
                            <td>{{item.total_amount}}</td>
                          </tr>
                        </tbody>
                      </template>
                    </v-simple-table>
                  </div>
                  <div v-else class="mt-4 subtitle-1 text-center" align="center">No Items selected</div>
                </v-row>
                <v-row justify="end" align="end">
                  <v-subheader class="subtitle-2">Rs.{{selected_total_amount}}</v-subheader>
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
    </v-card>
    <v-snackbar v-model="snackbar">
      {{ text }}
      <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>


<script>
export default {
  data() {
    return {
      bread_crumb_items: [
        {
          text: "Bilty"
        },
        {
          text: "Receive Bilty"
        }
      ],
      snackbar: false,
      text: "",
      delivered: false,
      paid: false,
      search: "",
      expanded: [],
      selected: [],
      loading: false,
      totalBilties: 0,
      options: {},
      singleExpand: false,
      headers_packages: [
        {
          text: "Description",
          align: "left",
          value: "description",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Unit",
          value: "unit",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Quantity",
          value: "quantity",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Weight",
          value: "total_weight",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Volume",
          value: "total_volume",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Labour",
          value: "labour",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Rate",
          value: "rate",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Rent",
          value: "rent",
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
          text: "Truck No",
          value: "truck_no",
          sortable: false,
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
      packages: []
    };
  },
  computed: {
    selected_total_amount() {
      let total_amount = 0;
      for (let item of this.selected) {
        total_amount += parseFloat(item.total_amount);
      }
      return total_amount;
    }
  },
  watch: {
    // watch for any changes in table options and trigger function
    options: {
      handler() {
        this.getBiltiesServerSide().then(data => {
          this.bilties = data.items;
          this.totalBilties = data.total;
        });
      },
      deep: true
    },
    delivered(delivered) {
      if (delivered == "delivered") {
        let selected = this.selected;
        if (selected.length > 0) {
          this.delivered = false;
          this.setDelivered();
        } else {
          this.snackbar = true;
          this.text = "Please select bilties first.";
        }
      }
    },
    paid(paid) {
      if (paid == "paid") {
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
  mounted() {
    this.getBiltiesServerSide().then(data => {
      this.bilties = data.items;
      this.totalBilties = data.total;
    });
    this.listenChallan();
    this.listenReceive();
  },
  methods: {
    changeDateFormat(date) {
      let dateSplit = date.split("-");
      return dateSplit[2] + "-" + dateSplit[1] + "-" + dateSplit[0];
    },
    listenChallan() {
      Echo.channel("challans").listen("ChallanAdded", challans => {
        this.getBiltiesServerSide().then(data => {
          this.bilties = data.items;
          this.totalBilties = data.total;
        });
        this.snackbar = true;
        this.text = "New data added";
      });
    },
    listenReceive() {
      Echo.channel("bilties").listen("BiltyAdded", bilty => {
        this.getBiltiesServerSide().then(data => {
          this.bilties = data.items;
          this.totalBilties = data.total;
        });
        this.snackbar = true;
        this.text = "Bilties status updated";
      });
    },
    // clearing up search
    defaultBilty() {
      this.search = "";
      this.getBiltiesServerSide().then(data => {
        this.bilties = data.items;
        this.totalBilties = data.total;
      });
    },
    // if search button is clicked or input given
    searchBilty() {
      this.getBiltiesServerSide().then(data => {
        this.bilties = data.items;
        this.totalBilties = data.total;
      });
    },
    // fetch bilties from backend
    getBiltiesServerSide() {
      this.loading = true;
      // using async function to wait for the functions to execute and release results
      return new Promise(async (resolve, reject) => {
        const { sortBy, sortDesc, page, itemsPerPage } = this.options;
        var items = [];
        var total = 0;
        var flag = false;

        // if search is given fetch searched item
        // else if check if sortBy and descending is given fetch items orderBy
        // else fetch all bilties by Latest
        if (this.search) {
          // searching
          await this.getBiltiesFiltered(page, itemsPerPage, this.search).then(
            data => {
              items = data.data;
              total = data.total_items;
              flag = true;
            }
          );
        } else if (sortBy.length === 1 && sortDesc.length === 1) {
          // sorting
          await this.getBiltiesSorted(
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
          await this.getBilties(page, itemsPerPage).then(data => {
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
    async getBilties(page, items_per_page) {
      let bilties = {};
      await this.$http({
        url: `bilties/receive/paginate?page=${page}&per_page=${items_per_page}`,
        method: "GET"
      }).then(
        res => {
          bilties.data = this.addBiltyData(res.data.data);
          bilties.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching bilties, please refresh";
        }
      );
      return bilties;
    },
    async getBiltiesFiltered(page, items_per_page, search) {
      let bilties = {};
      await this.$http({
        url: `bilties/receive/search?page=${page}&per_page=${items_per_page}&query=${search}`,
        method: "GET"
      }).then(
        res => {
          bilties.data = this.addBiltyData(res.data.data);
          bilties.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Can not find data";
        }
      );
      return bilties;
    },
    async getBiltiesSorted(page, items_per_page, sortBy, sortDesc) {
      let bilties = {};
      await this.$http({
        url: `bilties/receive/sort?page=${page}&per_page=${items_per_page}&sort_by=${sortBy}&sort_desc=${sortDesc}`,
        method: "GET"
      }).then(
        res => {
          bilties.data = this.addBiltyData(res.data.data);
          bilties.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching bilties, please refresh";
        }
      );
      return bilties;
    },
    addBiltyData(data) {
      let bilties = [];
      for (let bilty of data) {
        if (bilty.attributes.status != "dispatched") {
          continue;
        }
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
          created_at: this.changeDateFormat(
            bilty.attributes.created_at.slice(0, 10)
          ),
          bilty_charges: bilty.attributes.bilty_charges,
          local_charges: bilty.attributes.local_charges,
          packages: [],
          package_total: bilty.attributes.packages_total,
          total_amount: bilty.attributes.bilty_total,
          truck_no: ""
        };
        if (bilty.relationships.challan.data) {
          this.getChallanTruck(bilty.relationships.challan.data.id).then(
            res => {
              bilty_data.truck_no = res;
            }
          );
        }
        if (bilty.relationships.customer.data) {
          this.getCustomer(bilty.relationships.customer.data.id).then(res => {
            bilty_data.customer = res;
          });
        }
        for (let pck of bilty.relationships.packages.data) {
          this.getPackage(pck.id).then(res => {
            bilty_data.packages.push(res);
          });
        }
        bilties.push(bilty_data);
      }
      return bilties;
    },
    async getChallanTruck(id) {
      let truck_no = "";
      await this.$http({
        url: `challan/truck_no/${id}`,
        method: "GET"
      }).then(
        res => {
          truck_no = res.data;
        },
        () => {}
      );
      return truck_no;
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
        () => {}
      );
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
        },
        () => {}
      );
      return pck;
    },
    setPaid() {
      let bilties_id = [];
      for (let item of this.selected) {
        if (item.payment_status != "unpaid") {
          this.snackbar = true;
          this.text = "Can only receive payments of unpaid bilties.";
          return;
        }
        bilties_id.push(item.id);
      }
      this.$http({
        url: `bilties/payment/${"paid"}`,
        data: bilties_id,
        method: "POST"
      }).then(
        res => {
          this.getBiltiesServerSide().then(data => {
            this.bilties = data.items;
            this.totalBilties = data.total;
          });
          this.snackbar = true;
          this.text = "successfully changed status to paid";
        },
        err => {
          this.snackbar = true;
          this.text = "Error: " + err.response.statusText;
        }
      );
    },
    setDelivered() {
      let bilties_id = [];
      for (let item of this.selected) {
        if (item.payment_status == "unpaid") {
          this.snackbar = true;
          this.text =
            "Can not change status of unpaid bilty, please make sure every bilty is paid or monthly.";
          return;
        }
        bilties_id.push(item.id);
      }
      this.$http({
        url: `bilties/status/${"delivered"}`,
        data: bilties_id,
        method: "POST"
      }).then(
        res => {
          this.getBiltiesServerSide().then(data => {
            this.bilties = data.items;
            this.totalBilties = data.total;
          });
          this.snackbar = true;
          this.text = "successfully changed status";
        },
        err => {
          this.snackbar = true;
          this.text = "Error: " + err.response.statusText;
        }
      );
    }
  }
};
</script>
