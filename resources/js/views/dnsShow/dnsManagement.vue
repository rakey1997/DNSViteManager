<template>
    <makeFile :genFileData="genFileData"/>
    <DNSPage :queryForm="queryForm" />
</template>

<script>
    import DNSPage from "./components/dnsPage.vue";
    import makeFile from "./components/makeFile.vue";

    export default {
        name:'dnsManagement',
        components:{DNSPage,makeFile},
        setup(){
            const queryForm={
                filterNameList:['根区管理服务器']
            }
            const fileData={
                type:"server",
                fileNameList:{
                    fileName:"hosts-management-all.ini",
                    fileV6Name:"hosts-management-all-v6.ini",
                    whiteFileName:"whiteList-management.ini",
                    whiteV6FileName:"whiteList-management-v6.ini",
                    specialFileName:"hosts-management-special-all.ini",
                    specialV6FileName:"hosts-management-special-all-v6.ini",
                }
            }

            const genFileData={...queryForm, ...fileData}

            return{
                queryForm,
                genFileData
            }
        }
    }
</script>