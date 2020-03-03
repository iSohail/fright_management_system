<template>
  <v-form v-model="valid" ref = "form_1">
    <v-container class = "px-8 py-2">
      <v-row>
        <v-col cols="12" md="3">
          <v-text-field :disabled="!enabled" :rules="biltyNoRule"  v-model = "biltyNo" label="Builty Number" required></v-text-field>
        </v-col>
        <v-col cols="12" md="2">
          <v-checkbox v-model="enabled" hide-details class="shrink" label="Manual"></v-checkbox>
        </v-col>

        <v-col cols="12" md="7">
          <!-- <v-text-field v-model="customerId" :rules="nameRules" label="Customer ID" required></v-text-field> -->


          
      
      
        <v-row>
          
         
        </v-row>
      
              <v-autocomplete
                v-model="friends"
                :disabled="isUpdating"
                :items="customers"
                chips
                item-text="name"
                item-value="name"
                label="Customer"
                :rules = "customerRules"
                required
                
              >
                <template v-slot:selection="data">
                  
                    <v-chip>
                    {{ data.item.name }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template v-if="typeof data.item !== 'object'">
                    <v-list-item-content v-text="data.item"></v-list-item-content>
                  </template>
                  <template v-else>
                    <v-list-item-avatar v-html = "data.item.no">
                     
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title v-html="data.item.name"></v-list-item-title>
                      <v-list-item-subtitle v-html="data.item.company"></v-list-item-subtitle>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
              
      <!-- <v-divider></v-divider> -->
     



        </v-col>

      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="Lc_No" :rules="Lc_no_Rules" label="L/C - B/L No." required></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="biltyCharges" :rules="chargesRule" label="Bilty Charges" required></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="localCharges" :rules="chargesRule" label="Local Charges" required></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-radio-group v-model="row" row>
            <span class="mr-4">From</span>
            <v-radio label="Karachi" value="karachi"></v-radio>
            <v-radio label="Others" value="others"></v-radio>
          </v-radio-group>
        </v-col>

        <v-col cols="12" md="6">
          <v-radio-group v-model="row" row>
            <span class="mr-4">To</span>

            <v-radio label="Lahore" value="lahoree"></v-radio>
            <v-radio label="Sheikhupura" value="sheikhupura"></v-radio>
            <v-radio label="Others" value="others"></v-radio>
          </v-radio-group>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="sender" :rules="nameRules" label="Sender Name" required></v-text-field>
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field v-model="reciever" :rules="nameRules" label="Reciever Name" required></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="12">
          <v-textarea
            label="Reciever Address"
            rows="2"
            row-height="20"
            v-model = "outlined"
            class="ma-1"
            :rules= "AddressRule"

            required
          ></v-textarea>
        </v-col>
      </v-row>

      <v-row>
        <v-col class="d-flex" cols="6" md="4">
          <v-select :items="items3" :rules= "customerRules" label="Status" required></v-select>
        </v-col>
        <v-col cols="6" md="4">
          <v-select :items="items4" :rules= "customerRules" label="Payment Status" required></v-select>
          <!-- <v-radio-group v-model="row" row>
            <span class="mr-4">Paid</span>
            <v-radio label="Paid" value="paid"></v-radio>
            <v-radio label="Unpaid" value="unpaid"></v-radio>
          </v-radio-group>-->
        </v-col>
      </v-row>

      <v-data-table :headers="headers" :items="desserts" sort-by="calories" dark class="elevation-1">
        <template v-slot:top>
          <v-toolbar flat dark>
            <v-toolbar-title >Package Details</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="800px">
              <template v-slot:activator="{ on }">
                <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
              </template>
              <v-card >
                <v-card-title >
                  <span class="headline" color = "blue" >{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-form  ref = "form" >
                  <v-container>
                    <v-row>
                      <v-col cols="12" sm="12" md="6">
                        <v-text-field v-model="editedItem.packages" disabled :rules = "packageNoRule" required label="No. of Packages"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" sm="12" md="10">
                        <v-textarea 
                        label="Description"
                        v-model = editedItem.desription
                        :rules = "descriptionRule"
                        required
                        ></v-textarea>
                      </v-col>
                      </v-row>
                      <v-row>
                       <v-col cols="12" sm="8" md="6">
                        <v-text-field v-model="editedItem.quantity" :rules="chargesRule" required label="Quantity"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="8" md="6">
                        <v-text-field v-model="editedItem.unit" :rules="chargesRule" required label="Unit"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="8" md="4">
                        <v-text-field v-model="editedItem.total_weight" :rules="chargesRule" required label="Total Weight"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="8" md="4">
                        <v-text-field v-model="editedItem.rent" :rules="chargesRule" required label="Rent"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="8" md="4">
                        <v-text-field v-model="editedItem.labour" :rules="chargesRule" required label="Labour"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                  
                  </v-form>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                </v-card-actions>

                
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
          <v-btn class="primary mr-2" small @click="editItem(item)"><v-icon>mdi-pencil</v-icon></v-btn>
          <v-btn class="error" small @click="deleteItem(item)"><v-icon>mdi-delete</v-icon></v-btn>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
      <v-spacer></v-spacer>
      <v-btn class="my-4 float-right" @click="submit_bilty">Submit</v-btn>
    </v-container>
  </v-form>
</template>


<script>
export default {
  data: () => ({
  
      biltyNo :"",
      Lc_No : "",
      biltyCharges : "",
      localCharges : "",
      row : "",
      sender : "",
      reciever : "",
      outlined : "",
      autoUpdate: true,
      friends: [],
      isUpdating: false,
      name: 'Midnight Crew',
      customers: [
        // { header: 'Group 1' },
        { no  : '1' , name: 'Sandra Adams', company: 'Group 1'},
        { no  : '1' ,name: 'Ali Connors', company: 'Group 1' },
        { no  : '1' ,name: 'Trevor Hansen', company: 'Group 1'},
        { no  : '1' ,name: 'Tucker Smith', company: 'Group 1', },
        // { divider: true },
        // { header: 'Group 2' },
        { no  : '1' ,name: 'Britta Holt', company: 'Group 2'},
        { no  : '1' ,name: 'Jane Smith ', company: 'Group 2' },
        { no  : '1' ,name: 'John Smith', company: 'Group 2'  },
        { no  : '1' ,name: 'Sandra Williams', company: 'Group 2'},
      ],
      title: 'The summer breeze',
    
    dialog: false,
    enabled: false,

    headers: [
      {
        text: "Package No",
        align: "left",
        sortable: false,
        value: "packages"
      },
      { text: "Description", value: "" },
      {text : "Unit" , value : "" },
      {text : "Quantity" , value : "" },
      {text : "Total weight" , value : "" },
      { text: "Labour", value: "carbs" },
      
      { text: "Rent", value: "fat" },
      // { text: "Builty", value: "protein" },
      { text: "Action", value: "action", sortable: false }
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
      packages: "",
      desription : "",
      quantity : "",
      unit : "",
      total_weight : "",
      rent : "",
      labour : "",
    },
    defaultItem: {
      packages: "",
      desription : "",
      quantity : "",
      unit : "",
      total_weight : "",
      rent : "",
      labour : "",
      
    },

    items3: ["Registered", "Dispatched", "Delivered"],
    items4: ["Paid", "Unpaid", "Monthly"],

    valid: true,
    name: "",
    nameRules: [
      v => !!v || "Name is required",
      v => (v && v.length <= 10) || "Name must be less than 10 characters"
    ],
    descriptionRule : [
      // v => v.length = null || "Description is required",
      v => !!v || "Description is required",
      v => (v && v.length <= 1000) || "Descriptipn must be less than 100 characters"
    ],
    packageNoRule : [
       v  => {
      if (!v.trim()) return true;
      if (!isNaN(parseFloat(v)) && v >= 0 && v <= 99999) return true;
      return 'Number has to be between 0 and 99999';
    },
    v => !!v || "No of Packages is required",
    ],
    AddressRule: [
      v => !!v || "Address is required",
      v => (v && v.length <= 100) || "Address must be less than 100 characters"
    ],
    chargesRule: [
      v  => {
      if (!v.trim()) return true;
      if (!isNaN(parseFloat(v)) && v >= 0 && v <= 99999) return true;
      return 'Number has to be between 0 and 99999';
    },
    v => !!v || "Field can not be empty",
    ],
    Lc_no_Rules: [
      v => !!v || "L/C number is required",
      v => (v && v.length <= 10) || "Number must be less than 10 characters"
    ],
    biltyNoRule : [
      v  => {
      if (!v.trim()) return true;
      if (!isNaN(parseFloat(v)) && v >= 0 && v <= 99999) return true;
      return 'Number has to be between 0 and 99999';
    },
    ],
    customerRules : [
      v=> v != null || "Select one"
    ],
    email: "",
    emailRules: [
      v => !!v || "E-mail is required",
      v => /.+@.+\..+/.test(v) || "E-mail must be valid"
    ],
    select: "Karachi",
    items: ["Item 1", "Item 2", "Item 3", "Item 4"],
    checkbox: false,
    lazy: false
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.initialize();
  },
   watch: {
    isUpdating (val) {
      if (val) {
        setTimeout(() => (this.isUpdating = false), 3000)

      }

      // return this.mySelectDataValue == 0 || null // some comparison to determine its been selected
    },
  },

  methods: {
    remove (item) {
      const index = this.friends.indexOf(item.name)
      if (index >= 0) this.friends.splice(index, 1)
    },
    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
    },
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    initialize() {
      this.desserts = [
        {
          packages: "Frozen Yogurt",
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0
        }
      ];
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.desserts.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.desserts.splice(index, 1);
    },

    close() {
      this.dialog = false;
      this.resetValidation()
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
     
    save() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
      } else {
        this.desserts.push(this.editedItem);
      }
      this.close();
      }
      
    },
    submit_bilty () {
      if (this.$refs.form_1.validate()) {
        console.log ("submit bilty");
      }
    }
  }
};
</script>