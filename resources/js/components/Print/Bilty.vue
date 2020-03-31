<template>
  <div id="app">
    <v-app id="inspire">
      <v-container class="padding-on-print" style="min-width: 1000px !important">
        <v-toolbar color="white" dense flat tile>
          <v-toolbar-items>
            <v-btn light text @click="print">
              <v-icon>mdi-printer</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-row class="px-4">
          <v-col>
            <v-row>
              <v-simple-table style="width: 100%" dense light>
                <template v-slot:default>
                  <thead class="blue lighten-4">
                    <tr>
                      <th class="text-left subtitle-2 black--text">Sender</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <v-list-item class="pl-2">
                          <v-list-item-content>
                            <v-list-item-subtitle
                              class="body-2 black--text"
                            >{{sender.toUpperCase()}}</v-list-item-subtitle>
                            <v-list-item-subtitle class="body-2 black--text">{{sender_cell}}</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-row>
            <v-row>
              <v-simple-table style="width: 100%" dense light>
                <template v-slot:default>
                  <thead class="blue lighten-4">
                    <tr>
                      <th class="text-left subtitle-2 black--text">Received By</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <v-list-item class="pl-2">
                          <v-list-item-content>
                            <v-list-item-subtitle
                              class="body-2 black--text"
                            >{{receiver.toUpperCase()}}</v-list-item-subtitle>
                            <v-list-item-subtitle class="body-2 black--text">{{receiver_address}}</v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-row>
          </v-col>
          <v-col>
            <v-row justify="end">
              <v-simple-table dense style="width: 50%; ">
                <template v-slot:default>
                  <thead class="blue lighten-4">
                    <tr>
                      <th class="text-left subtitle-2 black--text">Bilty No</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{bilty_no}}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-row>
            <v-row justify="end">
              <v-simple-table dense style="width: 50%; ">
                <template v-slot:default>
                  <thead class="blue lighten-4">
                    <tr>
                      <th class="text-left subtitle-2 black--text">L/C-B/L No / Container No</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{container_no}}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-row>
            <v-row justify="end">
              <v-simple-table dense style="width: 50%; ">
                <template v-slot:default>
                  <thead class="blue lighten-4">
                    <tr>
                      <th class="text-left subtitle-2 black--text">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{created}}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-row>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-data-table
              light
              :headers="headers_selected_item"
              :items="selected_items"
              mobile-breakpoint="NaN"
              hide-default-footer
              disable-pagination
              disable-sort
              disable-filtering
              flat
              dense
              class="table_data_style"
            ></v-data-table>
          </v-col>
        </v-row>
        <v-row v-if="bilty_charges" justify="end" align="end">
          <p class="title mb-2 pr-4 subtitle-2">+ Bilty Charges: Rs.{{bilty_charges}}</p>
        </v-row>
        <v-row v-if="local_charges" justify="end" align="end">
          <p class="title mb-2 pr-4 subtitle-2">+ Local Charges: Rs.{{local_charges}}</p>
        </v-row>
        <v-row justify="end" align="end">
          <v-subheader
            class="title black--text font-weight-medium subtitle-1"
          >Net Total: Rs.{{bilty_total}}</v-subheader>
        </v-row>
      </v-container>
    </v-app>
  </div>
</template>

<script>
export default {
  data() {
    return {
      bilty_no: "",
      container_no: "",
      sender: "",
      sender_cell: "",
      receiver: "",
      receiver_address: "",
      created: "",
      selected_items: [],
      bilty_total: "",
      bilty_charges: "",
      local_charges: "",
      // count: 0,
      headers_selected_item: [
        {
          text: "#",
          align: "left",
          value: "count",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Description",
          value: "description",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Quantity",
          value: "quantity",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Unit",
          value: "unit",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Weight",
          value: "total_weight",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Rent",
          value: "rent",
          class: "blue lighten-4 black--text subtitle-2"
        }
      ]
    };
  },
  created() {
    console.log(this.$route.query.id);
    let bilty = this.$store.getters.getBiltyById(
      this.$route.query.id,
      this.$route.query.manual
    );
    console.log(bilty);
    if (bilty) {
      this.bilty_no = bilty.bilty_no;
      this.created = bilty.date;
      this.sender = bilty.sender;
      this.receiver = bilty.receiver;
      this.receiver_address = bilty.receiver_address;
      this.container_no = bilty.lc_bl_no;
      this.bilty_total = bilty.bilty_total;
      this.bilty_charges = bilty.bilty_charges;
      this.local_charges = bilty.local_charges;
      bilty.packages.forEach((element, index) => {
        element.count = index + 1;
      });
      console.log(bilty.packages);
      this.selected_items = bilty.packages;
    }
    // console.log(this.selected_items);
  },
  methods: {
    print() {
      window.print();
    }
  }
};
</script>

<style scoped>
@page {
  size: auto; /* auto is the initial value */

  /* this affects the margin in the printer settings */
  margin: 5mm 5mm 5mm 5mm;
}
</style>

