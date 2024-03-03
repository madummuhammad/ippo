<script>
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import { ref, watch } from "vue";
import { Head, useForm, Link } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
  setup(){
    var isLoading=ref(false);
    let ingredientForm=ref(useForm({
      name:null,
      purchase_price:null,
      ingredient_category_id:null,
      purchase_unit:null,
      code:null,
      consumtion_unit:null,
      conversion_rate:null,
      const_per_unit:null,
      stock:null,
      min_stock:null,
    }));

    const submit= ()=>{
      isLoading.value=true;
      console.log('ingredientForm',ingredientForm.value)
      ingredientForm.value.transform((data) => ({
          ...data
      }))
      .post(route('ingredient.store'), {
        onSuccess: () => {
          isLoading.value=false;
        },
        onError:(errors)=>{
          isLoading.value=false;
          console.log('error')
        }
      });
    }

    let categoryData=ref([])
    let categoryOption=ref([])
    const getCategory=async ()=> {
      try {
        const response = await axios.get('/api/ingredient_category/list');
        const data = response.data;
        console.log('list',data);

        if(data.status=='success'){
          categoryData.value=data.data;

          data.data.forEach((item,index)=>{
            categoryOption.value[index]={
              value:item.id,
              label:item.name
            }
          })
        }
      } catch (error) {
        console.error('error', error);
      }
    }
    
    getCategory()

    let unitData=ref([])
    let unitOption=ref([])
    const getUnit=async ()=> {
      try {
        const response = await axios.get('/api/ingredient_unit/list');
        const data = response.data;
        console.log('list',data);

        if(data.status=='success'){
          unitData.value=data.data;

          data.data.forEach((item,index)=>{
            unitOption.value[index]={
              value:item.id,
              label:item.name
            }
          })
        }
      } catch (error) {
        console.error('error', error);
      }
    }
    
    getUnit()

    watch(() => ingredientForm.value.conversion_rate, (newFields, oldFields) => {

      if(ingredientForm.value.purchase_price){
        ingredientForm.value.cost_per_unit=ingredientForm.value.purchase_price/ingredientForm.value.conversion_rate;
      }
      console.log(newFields)
    }, { deep: true });

    watch(() => ingredientForm.value.purchase_price, (newFields, oldFields) => {

      if(ingredientForm.value.conversion_rate){
        ingredientForm.value.cost_per_unit=ingredientForm.value.purchase_price/ingredientForm.value.conversion_rate;
      }
      console.log(newFields)
    }, { deep: true });

    return {
      isLoading,
      ingredientForm,
      categoryOption,
      unitOption,
      submit
    }
  },
  components: {
    Layout,
    PageHeader,
    flatPickr,
    Multiselect,
    Loading,
    Head
  },
};
</script>

<template>
  <div class="vl-parent">
    <loading v-model:active="isLoading" :loader="'dots'"/>
</div>
  <Layout>
    <Head title="New Ingredient" />
    <PageHeader title="New Ingredient" pageTitle="Ingredient" />
    <BRow>
      <BCol lg="12">
        <BCard no-body>
            <BCardHeader>
              <h5 class="card-title mb-0">Create Ingredient</h5>
            </BCardHeader>
            <BCardBody>
              <BRow class="g-4">
                <!-- Nama -->
                <BCol lg="4">
                  <div>
                    <label for="nameInput" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nameInput" :class="{'is-invalid': ingredientForm.errors.name}" placeholder="Enter name..." v-model="ingredientForm.name" required />
                    <div class="invalid-feedback" v-if="ingredientForm.errors.name">
                      {{ ingredientForm.errors.name }}
                    </div>
                  </div>
                </BCol>

                <!-- Email -->
                <BCol lg="4">
                  <div>
                    <label for="emailInput" class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="emailInput" :class="{'is-invalid': ingredientForm.errors.code}" placeholder="Enter code..." v-model="ingredientForm.code" required />
                    <div class="invalid-feedback" v-if="ingredientForm.errors.code">
                      {{ ingredientForm.errors.code }}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Category</label>
                    <div class="form-control p-0"  :class="{'is-invalid':ingredientForm.errors.ingredient_category_id}" >
                      <Multiselect placeholder="Enter category..." v-model="ingredientForm.ingredient_category_id" :options="categoryOption" :close-on-select="true" :searchable="true"  />
                    </div>
                    <div class="invalid-feedback">
                      {{ingredientForm.errors.ingredient_category_id}}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Purchase Unit</label>
                    <div class="form-control p-0"  :class="{'is-invalid':ingredientForm.errors.purchase_unit}" >
                      <Multiselect placeholder="Enter purchase unit..." v-model="ingredientForm.purchase_unit" :options="unitOption" :close-on-select="true" :searchable="true"  />
                    </div>
                    <div class="invalid-feedback">
                      {{ingredientForm.errors.purchase_unit}}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Consumtion Unit</label>
                    <div class="form-control p-0"  :class="{'is-invalid':ingredientForm.errors.consumtion_unit}" >
                      <Multiselect placeholder="Enter consumtion unit" v-model="ingredientForm.consumtion_unit" :options="unitOption" :close-on-select="true" :searchable="true"  />
                    </div>
                    <span class="text-sm fw-bold fst-italic">In which unit you make food</span>
                    <div class="invalid-feedback">
                      {{ingredientForm.errors.consumtion_unit}}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Conversion Rate</label>
                    <input type="number" class="form-control" id="emailInput" :class="{'is-invalid': ingredientForm.errors.conversion_rate}" placeholder="Enter conversion rate..." v-model="ingredientForm.conversion_rate" required />
                    <span class="text-sm fw-bold fst-italic">How many Consumption Unit is equal for 1 Purchase Unit</span>
                    <div class="invalid-feedback" v-if="ingredientForm.errors.conversion_rate">
                      {{ ingredientForm.errors.conversion_rate }}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Purchase Price</label>
                    <input type="number" class="form-control" id="emailInput" :class="{'is-invalid': ingredientForm.errors.purchase_price}" placeholder="Enter purchase price..." v-model="ingredientForm.purchase_price" required />
                    <div class="invalid-feedback" v-if="ingredientForm.errors.purchase_price">
                      {{ ingredientForm.errors.purchase_price }}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Cost Per Unit</label>
                    <input type="number" class="form-control" id="emailInput" :class="{'is-invalid': ingredientForm.errors.cost_per_unit}" placeholder="Cost per unit..." v-model="ingredientForm.cost_per_unit" required readonly />
                    <div class="invalid-feedback" v-if="ingredientForm.errors.cost_per_unit">
                      {{ ingredientForm.errors.cost_per_unit }}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="emailInput" :class="{'is-invalid': ingredientForm.errors.stock}" placeholder="Enter min stock..." v-model="ingredientForm.stock" required />
                    <div class="invalid-feedback" v-if="ingredientForm.errors.stock">
                      {{ ingredientForm.errors.stock }}
                    </div>
                  </div>
                </BCol>
                <BCol lg="4">
                  <div class="mb-3 mb-lg-0">
                    <label for="choices-priority-input" class="form-label">Min Stock</label>
                    <input type="number" class="form-control" id="emailInput" :class="{'is-invalid': ingredientForm.errors.min_stock}" placeholder="Enter min stock..." v-model="ingredientForm.min_stock" required />
                    <div class="invalid-feedback" v-if="ingredientForm.errors.min_stock">
                      {{ ingredientForm.errors.min_stock }}
                    </div>
                  </div>
                </BCol>
                <!-- Tombol -->
                <BCol lg="12">
                  <div class="hstack justify-content-end gap-2">
                    <BButton type="button" variant="danger">Kembali</BButton>
                    <BButton type="submit" variant="primary" @click="submit">Submit</BButton>
                  </div>
                </BCol>
              </BRow>
            </BCardBody>
          </BCard>
        </BCol>
      </BRow>
    </Layout>
</template>