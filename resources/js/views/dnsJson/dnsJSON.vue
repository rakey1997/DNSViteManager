<template>
    <makeJSON :genFileData="fileData"/>
    <el-alert :title="remindTitle" type="success" center />
    <json-viewer :value="jsonData" boxed expanded :expand-depth="5" :copyable="copy" />
  </template>
  
  <script>
    import { ref,reactive, watch } from '@vue/runtime-core';
    import { getJSONInfos } from "../../api/dnsInfo";
    import { useI18n } from "vue-i18n";
    import {useStore} from "vuex";
    import makeJSON from "../dnsShow/components/makeJSON.vue";

    export default {
        name:'dnsJSON',
        components:{makeJSON},
        setup() {
            const jsonData=ref({})
            const {t}=useI18n()
            const store=useStore()

            const copy=reactive(
                    {
                    copyText: '',
                    copiedText: ''
                    }
                )
            let remindTitle=ref('')

            const fileData={
                type:"json",
                fileNameList:{
                    fileName:"Gen-base-org-product.json",
                }
            }

            const initGetJSONInfos=async ()=>{
                const res=await getJSONInfos()
                jsonData.value=res.data
            }

            initGetJSONInfos()
            
            watch(
                () => store.getters.lang,
                () => {
                    Object.keys(copy).forEach(key => {
                        copy[key]=t(`dnsTableTitle.${key}`)
                    });
                    remindTitle.value=t("dnsTableTitle.remindTitle")
                },
                { deep: true,immediate:true }
            );

            return{
                jsonData,
                copy,
                remindTitle,
                fileData
            }
        }
    }
  </script>
