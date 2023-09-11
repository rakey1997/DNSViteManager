<template>
    <el-button type="primary" icon="Files"  @click="createFile" :disabled="store.getters.role!='admin'">{{$t('table.genFiles')}}</el-button>
</template>

<script>
    import { genFiles } from "../../../api/dnsInfo";
    import { useI18n } from "vue-i18n";
    import {useStore} from "vuex";
    
    export default {
        name:"makeFile",
        props:{
            genFileData:{
                type:Object,
                default:()=>{},
            },
        },
        setup(props){
            const store=useStore()
            const {t}=useI18n()
            const createFile=async ()=>{
                const res=await genFiles(props.genFileData)
                if(res.opCode){
                    ElMessage({
                        type: 'success',
                        message: t('dialog.doneCreate'),
                    })
                }else{
                    ElMessage({
                        type: 'fail',
                        message: t('dialog.doneCreate'),
                    })
                }
            }

            return{
                props,
                store,
                createFile
            }
        }
    }
</script>

<style>

</style>