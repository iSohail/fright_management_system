<template>
  <v-container>
    <v-card>
      <v-card-title>
        Manage Customer
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table :headers="headers" :items="customers" :search="search">
        <template v-slot:top>
          <v-dialog v-model="dialog" max-width="800px">
            <v-card>
              <v-card-title>
                <span class="headline">Edit Item</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.no" label="Customer No"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.cellNo" label="Cell No"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.perKg" label="Per Kg"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.perCbm" label="Per Cbm"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editedItem.perPackage" label="Per Package"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </template>
        <template v-slot:item.action="{ item }">
          <v-btn class="primary mr-2" small @click="editItem(item)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <!-- <v-btn v-if="$auth.check(2)" class="error" small @click="deleteItem(item)">
            <v-icon>mdi-delete</v-icon>
          </v-btn>-->
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>



<script>
export default {
  data() {
    return {
      search: "",
      dialog: false,
      headers: [
        {
          text: "Customer No",
          align: "left",
          sortable: false,
          value: "no"
        },
        { text: "Name", value: "name" },
        { text: "Cell No", value: "cellNo" },
        { text: "Per Kg", value: "perKg" },
        { text: "Per Cbm", value: "perCbm" },
        { text: "Per Package", value: "perPackage" },
        { text: "Action", value: "action" }
      ],
      customers: [
        {
          id: "",
          no: "",
          name: "",
          cellNo: "",
          perKg: "",
          perCbm: "",
          perPackage: "",
          action: ""
        }
      ],
      editedIndex: -1,
      editedItem: {
        id: "",
        no: "",
        name: "",
        cellNo: "",
        perKg: "",
        perCbm: "",
        perPackage: ""
      }
    };
  },
  mounted() {
    this.getCustomers();
  },
  methods: {
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
              cellNo: customer.attributes.cell_no,
              perKg: customer.attributes.per_kg_rate,
              perCbm: customer.attributes.per_cbm_rate,
              perPackage: customer.attributes.per_pck_rate
            });
          }
          this.customers = customers;
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    updateCustomer(editedItem, id) {
      this.$http({
        url: `customer/${id}`,
        data: editedItem,
        method: "PUT"
      }).then(
        res => {
          this.getCustomers();
          console.log(res);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    deleteCustomer(id) {
      this.$http({
        url: `customer/${id}`,
        method: "DELETE"
      }).then(
        res => {
          this.getCustomers();
          console.log(res);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    editItem(item) {
      this.editedIndex = this.customers.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.customers.indexOf(item);
      let confirm_dlt = confirm("Are you sure you want to delete this item?");
      if (confirm_dlt) {
        this.deleteCustomer(item.id);
      }
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        this.updateCustomer(this.editedItem, this.editedItem.id);
        console.log("Edit done");
        // Object.assign(this.customers[this.editedIndex], this.editedItem);
      } else {
        this.customers.push(this.editedItem);
      }
      this.close();
    }
  }
};
</script>