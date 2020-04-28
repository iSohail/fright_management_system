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
      <v-card-title class="px-8 pt-8 headline">MANAGE BILTY</v-card-title>

      <v-card class="mx-8 my-2 mb-4">
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
          :single-expand="singleExpand"
          :expanded.sync="expanded"
        >
          <template v-slot:item.action="{ item }">
            <v-row>
              <v-col cols="6" class="px-0">
                <v-btn class="primary" small @click="editItem(item)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="6" class="px-0">
                <v-btn class="red mx-sm-3" small @click="printItem(item)">
                  <v-icon>mdi-printer</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length" class="black">
              <v-row v-if="item.customer">
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
                <v-col v-if="item.challan_no" cols="12" md="6">
                  <v-row>
                    <v-subheader>Challan Details</v-subheader>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        label="Challan no"
                        placeholder="Challan no"
                        v-model="item.challan_no"
                        readonly
                        outlined
                        dense
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col v-if="item.ledger_no" cols="12" md="6">
                  <v-row>
                    <v-subheader>Ledger Details</v-subheader>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        label="Ledger no"
                        placeholder="Ledger no"
                        v-model="item.ledger_no"
                        readonly
                        outlined
                        dense
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>

              <v-divider v-if="item.ledger_no || item.challan_no"></v-divider>
              <v-row>
                <v-subheader>Bilty Details</v-subheader>
              </v-row>
              <v-row>
                <v-col cols="12" md="4">
                  <v-text-field
                    label="Receiver"
                    placeholder="Receiver"
                    v-model="item.receiver"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field
                    label="Description"
                    placeholder="Description"
                    v-model="item.description"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field
                    label="Lc-Bl-No"
                    placeholder="Lc-Bl-No"
                    v-model="item.lc_bl_no"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
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
          text: "Manage Bilty"
        }
      ],
      snackbar: false,
      text: "",
      expanded: [],
      singleExpand: false,
      search: "",
      loading: false,
      totalBilties: 0,
      options: {},
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
          text: "Created",
          value: "created_at",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "User",
          value: "user_name",
          sortable: false,
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
      bilties: [],
      packages: []
    };
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
    }
  },
  mounted() {
    this.getBiltiesServerSide().then(data => {
      this.bilties = data.items;
      this.totalBilties = data.total;
    });
    this.listen();
  },
  methods: {
    changeDateFormat(date) {
      let dateSplit = date.split("-");
      return dateSplit[2] + "-" + dateSplit[1] + "-" + dateSplit[0];
    },
    listen() {
      Echo.channel("bilties").listen("BiltyAdded", bilties => {
        this.getBiltiesServerSide().then(data => {
          this.bilties = data.items;
          this.totalBilties = data.total;
        });
        this.snackbar = true;
        this.text = "New data added";
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
        url: `bilties/paginate?page=${page}&per_page=${items_per_page}`,
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
        url: `bilties/search?page=${page}&per_page=${items_per_page}&query=${search}`,
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
        url: `bilties/sort?page=${page}&per_page=${items_per_page}&sort_by=${sortBy}&sort_desc=${sortDesc}`,
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
    // utility function to make array of objects
    addBiltyData(data) {
      let bilties = [];
      for (let bilty of data) {
        let bilty_data = {
          id: bilty.id,
          no: bilty.attributes.bilty_no,
          lc_bl_no: bilty.attributes.lg_bl_no,
          manual: bilty.attributes.manual,
          date: this.changeDateFormat(bilty.attributes.created_at.slice(0, 10)),
          description: bilty.attributes.description,
          from: bilty.attributes.from,
          to: bilty.attributes.to,
          sender: bilty.attributes.sender,
          receiver: bilty.attributes.receiver,
          receiver_address: bilty.attributes.receiver_address,
          status: bilty.attributes.status,
          lock: bilty.attributes.lock,
          payment_status: bilty.attributes.payment_status,
          created_at: this.changeDateFormat(
            bilty.attributes.created_at.slice(0, 10)
          ),
          bilty_charges: bilty.attributes.bilty_charges,
          local_charges: bilty.attributes.local_charges,
          user_name: bilty.relationships.user.data.user_name,
          packages: [],
          package_total: bilty.attributes.packages_total,
          total_amount: bilty.attributes.bilty_total
        };
        if (bilty.relationships.customer.data) {
          this.getCustomer(bilty.relationships.customer.data.id).then(res => {
            bilty_data.customer = res;
          });
        }
        if (bilty.relationships.challan.data) {
          bilty_data.challan_no = bilty.relationships.challan.data.challan_no;
        }
        if (bilty.relationships.ledger.data) {
          bilty_data.ledger_no = bilty.relationships.ledger.data.ledger_no;
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
    // fetch bilties customer data
    async getCustomer(id) {
      let customer = {};
      await this.$http({
        url: `customer/${id}`,
        method: "GET"
      }).then(
        res => {
          customer = {
            id: id,
            customer_no: res.data.attributes.customer_no,
            name: res.data.attributes.name,
            company: res.data.attributes.company
          };
        },
        () => {}
      );
      return customer;
    },
    // fetch bilties packages data
    async getPackage(id) {
      let pck = {};
      await this.$http({
        url: `package/${id}`,
        method: "GET"
      }).then(
        res => {
          pck = {
            id: id,
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
    editItem(item) {
      if (item.lock == "false") {
        item.bilty_no = item.no;
        this.$store.dispatch("destroyEditBilty");
        this.$store.dispatch("createEditBilty", item);
        let user = this.$auth.user();
        if (user.role == 2) {
          this.$router.push({ path: `/admin/bilty/edit/${item.id}` });
        } else {
          this.$router.push({ path: `/operator/bilty/edit/${item.id}` });
        }
      } else {
        this.snackbar = true;
        this.text = "Bilty is locked";
      }
    },
    printItem(item) {
      item.bilty_no = item.no;
      this.$store.dispatch("destroyBilty");
      this.$store.dispatch("createBilty", item);

      let routeData = this.$router.resolve({
        name: "invoice.bilty",
        query: { id: item.bilty_no, manual: item.manual }
      });
      window.open(routeData.href, "_blank");
    }
  }
};
</script>