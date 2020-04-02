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
          <v-simple-table style="width: 100%">
            <template v-slot:default>
              <tbody class="subtitle-2">
                <tr>
                  <td style="width: 10%">Bilty No</td>
                  <td class="text-left font-weight-regular black--text">{{bilty_no}}</td>
                  <td style="width: 20%" class="text-right">Container / L-C/B-L No</td>
                  <td class="text-left font-weight-regular black--text">{{container_no}}</td>
                  <td style="width: 10%" class="text-right">Date</td>
                  <td class="text-left font-weight-regular black--text">{{created}}</td>
                </tr>
                <tr>
                  <td style="width: 10%">Sender</td>
                  <td class="text-left font-weight-regular black--text">{{sender.toUpperCase()}}</td>
                  <td style="width: 15%" class="text-right">Received By</td>
                  <td class="text-left font-weight-regular black--text">{{receiver.toUpperCase()}}</td>
                  <td style="width: 15%" class="text-right">Karachi To</td>
                  <td class="text-left font-weight-regular black--text">{{receiver.toUpperCase()}}</td>
                  <!-- <td>Karachi To</td>
                  <td class="font-weight-regular black--text">{{challan_no}}</td>-->
                </tr>
              </tbody>
            </template>
          </v-simple-table>
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
              class="table_data_style"
            ></v-data-table>
          </v-col>
        </v-row>
        <v-row class="mt-5">
          <v-simple-table dense style="width: 100%">
            <template v-slot:default>
              <tbody class="subtitle-2">
                <tr>
                  <td></td>
                  <td></td>
                  <td class="text-right blue lighten-5">+ Bilty Charges</td>
                  <td
                    class="text-left blue lighten-5 font-weight-regular black--text"
                  >Rs. {{bilty_charges}}</td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td class="text-right blue lighten-5">+ Local Charges</td>
                  <td
                    class="text-left blue lighten-5 font-weight-regular black--text"
                  >Rs. {{local_charges}}</td>
                </tr>
                <tr>
                  <td style="width: 15%">DELIVERY AT</td>
                  <td style="width: 55%">{{receiver_address}}</td>
                  <td class="text-right blue lighten-5">Net Total</td>
                  <td
                    class="text-left blue lighten-5 font-weight-regular black--text"
                  >Rs. {{bilty_total}}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
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
          text: "Labour",
          value: "labour",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Fright",
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
    console.log(bilty, "from store");
    if (bilty) {
      this.bilty_no = bilty.bilty_no;
      this.created = bilty.date;
      this.sender = bilty.sender;
      this.receiver = bilty.receiver;
      this.receiver_address = bilty.receiver_address;
      this.container_no = bilty.lc_bl_no;
      this.bilty_total = bilty.total_amount;
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

