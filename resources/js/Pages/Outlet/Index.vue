<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import { MoreHorizontalIcon } from "@zhuowenli/vue-feather-icons";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import { ref, watch } from "vue";
import animationData from "@/Components/widgets/gsqxdxog.json";

import Lottie from "@/Components/widgets/lottie.vue";
import { projectWidgets1, projectWidgets2, projectWidgets3 } from "@/common/data";
import Swal from "sweetalert2";
export default {
  setup(){
    let outletData=ref([])
    const getOutlet = async (event) => {
      try {
        const response = await axios.get('/api/outlet/list');
        const data = response.data;

        if(data.status=='success'){
          outletData.value=data.data;
        }

        console.log('outlet',data)
      } catch (error) {
        console.error('error', error);
      }
    };

    getOutlet();

    const destroy=(event)=>{
      Swal.fire({
          title: "Hapus Outlet?",
          text: "Semua data terkait outlet akan dihapus!",
          showCancelButton: true,
          cancelButtonColor: "#f46a6a",
          confirmButtonColor: "#34c38f",
          confirmButtonText: "Hapus!",
      }).then((result) => {
          if (result.isConfirmed) {
              const deleteEndpoint = 'api/outlet/destroy/'+event.id;

              axios.delete(deleteEndpoint)
                  .then(response => {
                    Swal.fire({
                      toast: true,
                      position: 'bottom-end',
                      showConfirmButton: false, 
                      timer: 3000, 
                      iconColor: 'white',
                      customClass: {
                          popup: 'colored-toast',
                      },
                      timerProgressBar: true, 
                      icon: 'success', 
                      title: 'Outlet berhasil dihapus.'
                    });
                    getOutlet()
                  })
                  .catch(error => {
                      console.error(error);
                  });
          }
      });

        }

    return {
    outletData,
    destroy
    }
  },
  data() {
  },
  components: {
    Layout,
    PageHeader,
    Multiselect,
    MoreHorizontalIcon,
    lottie: Lottie,
    Swal,
    Head, Link
  },
  methods: {
  }
};
</script>

<template>
  <Layout>

    <Head title="Outlet" />
    <PageHeader title="Outlet" pageTitle="Projects" />
    <BRow class="g-4 mb-3">
      <!-- <BCol sm="auto">
        <div>
          <Link href="/outlet/create" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i>
          Tambah Outlet</Link>
        </div>
      </BCol> -->
      <BCol sm>
        <div class="d-flex justify-content-sm-end gap-2">
          <div class="search-box ms-2">
            <input type="text" class="form-control" placeholder="Search..." />
            <i class="ri-search-line search-icon"></i>
          </div>

          <Multiselect class="multiselect form-control w-lg w-auto m-0" v-model="value" :close-on-select="true"
            :searchable="true" :create-option="true" :options="[
              { value: 'All', label: 'All' },
              { value: 'Today', label: 'Today' },
              { value: 'Yesterday', label: 'Yesterday' },
              { value: 'Last 7 Days', label: 'Last 7 Days' },
              { value: 'Last 30 Days', label: 'Last 30 Days' },
              { value: 'This Month', label: 'This Month' },
              { value: 'Last Year', label: 'Last Year' },
            ]" />
        </div>
      </BCol>
    </BRow>


    <BRow>
      <BCol xxl="3" sm="6" class="project-card" v-for="( item, index ) of  outletData " :key="index">
        <BCard no-body>
          <BCardBody>
            <div class="p-3 mt-n3 mx-n3 bg-subtle rounded-top bg-primary">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                  <h5 class="mb-0 fs-15">
                    <Link href="/apps/projects-overview" class="text-white">{{item.name}}</Link>
                  </h5>
                </div>
                <div class="flex-shrink-0">
                  <div class="d-flex gap-1 align-items-center my-n2">
                    <BDropdown variant="link"
                      toggle-class="btn btn-link text-white p-1 mt-n2 py-0 text-decoration-none fs-15 arrow-none"
                      menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }"
                      style="top: 3px;">
                      <template #button-content>
                        <MoreHorizontalIcon class="icon-sm"></MoreHorizontalIcon>
                      </template>
                      <Link class="dropdown-item" href="/apps/projects-overview"><i
                        class="ri-eye-fill align-bottom me-2 text-muted"></i>
                      View</Link>
                      <Link class="dropdown-item" :href="route('outlet.edit',item.id)"><i
                        class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                      Edit </Link>
                      <div class="dropdown-divider"></div>
                      <BDropdownItem @click="destroy(item)"> <i
                          class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                        Remove
                      </BDropdownItem>
                    </BDropdown>
                  </div>
                </div>
              </div>
            </div>

            <div class="py-3">
            <div class="card-image mb-3">
                <img class="img-fluid" :src="item.image">
            </div>
              <BRow class="gy-3">
                <BCol cols="4">
                    PIC
                </BCol>
                <BCol cols="8" class="text-capitalize">
                    : {{item.user.name}}
                </BCol>
              </BRow>
              <BRow class="gy-3">
                <BCol cols="4">
                    Alamat
                </BCol>
                <BCol cols="8" class="text-capitalize">
                    : {{item.address}}
                </BCol>
              </BRow>
            </div>
            <div>
                <button class="btn btn-primary w-100">Masuk</button>
            </div>
          </BCardBody>
        </BCard>
      </BCol>
      <BCol xxl="12" sm="12" class="project-card text-center" v-if="outletData.length==0">
        <div class="card p-5">
          Tidak ada data ditemukan
        </div>
      </BCol>
    </BRow>

  </Layout>
</template>