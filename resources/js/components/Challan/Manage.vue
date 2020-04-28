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
      <v-card-title class="px-8 pt-8 headline">MANAGE CHALLAN</v-card-title>
      <v-card-text>
        <v-card class="mx-4 my-2 mb-4">
          <v-card-title>
            Challans
            <v-spacer></v-spacer>
            <v-text-field
              @keyup.native.enter="searchChallan"
              v-model="search"
              append-icon="mdi-magnify"
              append-outer-icon="mdi-lock-reset"
              clear-icon="mdi-close-circle"
              clearable
              @click:append="searchChallan"
              @click:append-outer="defaultChallan"
              @click:clear="defaultChallan"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="challans"
            :options.sync="options"
            :server-items-length="totalChallan"
            :footer-props="{
              itemsPerPageOptions: [5, 10, 15],
            }"
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
                  <v-subheader>Challan Details</v-subheader>
                </v-row>
                <v-row>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Driver Name"
                      placeholder="Driver Name"
                      v-model="item.driver_name"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Agent Name"
                      placeholder="Agent Name"
                      v-model="item.agent_name"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Transport"
                      placeholder="Transport"
                      v-model="item.transport"
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
                <v-row>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Total Amount"
                      placeholder="Total Amount"
                      v-model="item.total_amount"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Expenses"
                      placeholder="Expenses"
                      v-model="item.expenses"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field
                      label="Grand Total"
                      placeholder="Grand Total"
                      v-model="item.grand_total"
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
          text: "Challan"
        },
        {
          text: "Manage Challan"
        }
      ],
      search: "",
      loading: false,
      totalChallan: 0,
      options: {},
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
          text: "Payment",
          value: "payment_status",
          class: "light-blue darken-3 white--text"
        }
      ],
      headers: [
        {
          text: "Challan No",
          align: "left",
          value: "no",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Truck No",
          value: "truck_no",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Permit No",
          value: "permit_no",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Grand Total",
          value: "grand_total",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "CNIC",
          value: "cnic",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Created",
          value: "date",
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
          align: "center",
          class: "light-blue darken-3 white--text "
        },
        {
          text: "",
          value: "data-table-expand",
          class: "light-blue darken-3 white--text"
        }
      ],
      challans: [],
      packages: []
    };
  },
  watch: {
    // watch for any changes in table options and trigger function
    options: {
      handler() {
        this.getChallansServerSide().then(data => {
          this.challans = data.items;
          this.totalChallan = data.total;
        });
      },
      deep: true
    }
  },
  mounted() {
    this.getChallansServerSide().then(data => {
      this.challans = data.items;
      this.totalChallan = data.total;
    });
    this.listen();
  },
  methods: {
    changeDateFormat(date) {
      let dateSplit = date.split("-");
      return dateSplit[2] + "-" + dateSplit[1] + "-" + dateSplit[0];
    },
    listen() {
      Echo.channel("challans").listen("ChallanAdded", challans => {
        this.getChallansServerSide().then(data => {
          this.challans = data.items;
          this.totalChallan = data.total;
        });
        this.snackbar = true;
        this.text = "New data added";
      });
    },
    // clearing up search
    defaultChallan() {
      this.search = "";
      this.getChallansServerSide().then(data => {
        this.challans = data.items;
        this.totalChallan = data.total;
      });
    },
    // if search button is clicked or input given
    searchChallan() {
      this.getChallansServerSide().then(data => {
        this.challans = data.items;
        this.totalChallan = data.total;
      });
    },
    // fetch challans from backend
    getChallansServerSide() {
      this.loading = true;
      // using async function to wait for the functions to execute and release results
      return new Promise(async (resolve, reject) => {
        const { sortBy, sortDesc, page, itemsPerPage } = this.options;

        var items = [];
        var total = 0;
        var flag = false;

        // if search is given fetch searched item
        // else if check if sortBy and descending is given fetch items orderBy
        // else fetch all challans by Latest
        if (this.search) {
          // searching
          await this.getChallansFiltered(page, itemsPerPage, this.search).then(
            data => {
              items = data.data;
              total = data.total_items;
              flag = true;
            }
          );
        } else if (sortBy.length === 1 && sortDesc.length === 1) {
          // sorting
          await this.getChallansSorted(
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
          await this.getChallans(page, itemsPerPage).then(data => {
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
    async getChallans(page, items_per_page) {
      let challans = {};
      await this.$http({
        url: `challans/paginate?page=${page}&per_page=${items_per_page}`,
        method: "GET"
      }).then(
        res => {
          challans.data = this.addChallanData(res.data.data);
          challans.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching challans, please refresh";
        }
      );
      return challans;
    },
    async getChallansFiltered(page, items_per_page, search) {
      let challans = {};
      await this.$http({
        url: `challans/search?page=${page}&per_page=${items_per_page}&query=${search}`,
        method: "GET"
      }).then(
        res => {
          challans.data = this.addChallanData(res.data.data);
          challans.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Can not find data";
        }
      );
      return challans;
    },
    async getChallansSorted(page, items_per_page, sortBy, sortDesc) {
      let challans = {};
      await this.$http({
        url: `challans/sort?page=${page}&per_page=${items_per_page}&sort_by=${sortBy}&sort_desc=${sortDesc}`,
        method: "GET"
      }).then(
        res => {
          challans.data = this.addChallanData(res.data.data);
          challans.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching challans, please refresh";
        }
      );
      return challans;
    },
    // utility function to make array of objects
    addChallanData(data) {
      let challans = [];
      for (let challan of data) {
        let challan_data = {
          id: challan.id,
          no: challan.attributes.challan_no,
          date: this.changeDateFormat(
            challan.attributes.created_at.slice(0, 10)
          ),
          truck_no: challan.attributes.truck_no,
          from: challan.attributes.from,
          permit_no: challan.attributes.permit_no,
          to: challan.attributes.to,
          transport: challan.attributes.transport,
          driver_name: challan.attributes.driver_name,
          agent_name: challan.attributes.agent_name,
          cnic: challan.attributes.cnic,
          total_amount: challan.attributes.total_amount,
          user_name: challan.relationships.user.data.user_name,
          expenses: challan.attributes.expenses,
          grand_total: challan.attributes.grand_total,
          bilties: []
        };
        for (let bilty of challan.relationships.bilties.data) {
          this.getBilty(bilty.id).then(res => {
            challan_data.bilties.push(res);
          });
        }
        challans.push(challan_data);
      }
      return challans;
    },
    async getBilty(id) {
      let bilty = {};
      await this.$http({
        url: `bilty/${id}`,
        method: "GET"
      }).then(
        res => {
          bilty = {
            id: res.data.id,
            no: res.data.attributes.bilty_no,
            lc_bl_no: res.data.attributes.lg_bl_no,
            manual: res.data.attributes.manual,
            sender: res.data.attributes.sender,
            receiver: res.data.attributes.receiver,
            payment_status: res.data.attributes.payment_status
          };
        },
        () => {}
      );
      return bilty;
    },
    editItem(item) {
      let user = this.$auth.user();
      if (user.role == 2) {
        this.$router.push({ path: `/admin/challan/edit/${item.id}` });
      } else {
        this.$router.push({ path: `/operator/challan/edit/${item.id}` });
      }
    },
    printItem(item) {
      item.challan_no = item.no;
      let bilties = [];
      item.bilties.forEach((element, index) => {
        bilties.push(element.id);
      });
      item.bilties = bilties;
      this.$store.dispatch("destroyChallan");
      this.$store.dispatch("createChallan", item);
      let routeData = this.$router.resolve({
        name: "invoice.challan",
        query: { id: item.challan_no }
      });
      window.open(routeData.href, "_blank");
    }
  }
};
</script>