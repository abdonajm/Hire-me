<template>
    <h1>List des Annonces candidat</h1>

    <div class="Table_data">


        <vue-good-table :columns="columns" :rows="AnnonceCandidat" :select-options="{enabled: false }" :pagination-options="{
    enabled: true,
    mode: 'records',
    perPage: 5,
    position: 'bottom',
    perPageDropdown: [3, 7, 9],
    dropdownAllowAll: false,
    setCurrentPage: 2,
    nextLabel: 'next',
    prevLabel: 'prev',
    rowsPerPageLabel: 'Rows per page',
    ofLabel: 'of',
    pageLabel: 'page', // for 'pages' mode
    allLabel: 'All',
    infoFn: (params) => `page ${params.firstRecordOnPage}`, 
  }">
            <template #table-row="props">
                <span v-if="props.column.field == 'action'">
                    <button type="button" @click="deleteAnnonce(props.row.annonce_id)" class="btn-danger !important"><i
                            class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </span>
            </template>

        </vue-good-table>

    </div>
</template>

<script>
    import useAnnonces from '../../../store/annonceStore.js'
    import {
        onMounted
    } from 'vue'
    import 'vue-good-table-next/dist/vue-good-table-next.css'
    import {
        VueGoodTable
    } from 'vue-good-table-next';
    export default {
        setup() {

                const {
                    AnnonceCandidat,
                    getAnnoncesCandidat,
                    destroyAnnonce
                } = useAnnonces()

                function deleteAnnonce(id){
                    destroyAnnonce(id)
                    getAnnoncesCandidat()
                }
                onMounted(getAnnoncesCandidat())


            return {
                AnnonceCandidat,
                deleteAnnonce,

            }
        },

        components: {
            VueGoodTable,

        },

        data() {
            return {
                columns: [{
                        label: 'Titre annonce',
                        field: 'titre_annonce',
                        type: 'text',
                    },
                    {
                        label: 'Type annonce',
                        field: 'type_annonce',
                        type: 'text',
                    },
                    {
                        label: 'Description annonce',
                        field: 'description_annonce',
                        type: 'text',
                    },
                    {
                        label: 'Niveau Etude',
                        field: 'niveau_etude',
                        type: 'text',
                    },
                    {
                        label: 'username',
                        field: 'username',
                        type: 'text',
                    },
                    {
                        label: "Secteur d'activite",
                        field: "secteur_activite",
                        type: 'text',
                    },
                    {
                        label: 'Action',
                        field: 'action',
                        type: 'input'
                    }
                ],
            }
        }
    }

</script>

<style scoped>

</style>
