<script>
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import { ref, watch } from "vue";
import { Head, useForm, Link } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
export default {
  setup(){
    var isLoading=ref(false);
    let categoryForm=ref(useForm({
      name:null,
      description:null,
    }));

    const submit= ()=>{
      isLoading.value=true;
      console.log('categoryForm',categoryForm.value)
      categoryForm.value.transform((data) => ({
          ...data
      }))
      .post(route('ingredient_category.store'), {
        onSuccess: () => {
          isLoading.value=false;
        },
        onError:(errors)=>{
          isLoading.value=false;
          console.log('error')
        }
      });
    }


    return {
      isLoading,
      categoryForm,
      submit
    }
  },
  components: {
    Layout,
    PageHeader,
    flatPickr,
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
    <Head title="New Ingredient Category" />
    <PageHeader title="New Ingredient Category" pageTitle="Ingredient" />
    <BRow>
      <BCol lg="12">
        <BCard no-body>
            <BCardHeader>
              <h5 class="card-title mb-0">Create Ingredient Category</h5>
            </BCardHeader>
            <BCardBody>
              <BRow class="g-4">
                <!-- Nama -->
                <BCol lg="6">
                  <div>
                    <label for="nameInput" class="form-label">Categoru Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nameInput" :class="{'is-invalid': categoryForm.errors.name}" placeholder="Enter category name..." v-model="categoryForm.name" required />
                    <div class="invalid-feedback" v-if="categoryForm.errors.name">
                      {{ categoryForm.errors.name }}
                    </div>
                  </div>
                </BCol>

                <!-- Email -->
                <BCol lg="6">
                  <div>
                    <label for="emailInput" class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="emailInput" :class="{'is-invalid': categoryForm.errors.description}" placeholder="Enter description..." v-model="categoryForm.description" required />
                    <div class="invalid-feedback" v-if="categoryForm.errors.description">
                      {{ categoryForm.errors.description }}
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