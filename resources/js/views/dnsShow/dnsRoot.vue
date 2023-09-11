<template>
    <makeFile :genFileData="genFileData"/>
    <DNSPage :queryForm="queryForm" />
</template>

<script>
    import DNSPage from "./components/dnsPage.vue";
    import makeFile from "./components/makeFile.vue";

    export default {
        name:'dnsRoot',
        components:{DNSPage,makeFile},
        setup(){
            const queryForm={
                filterNameList:['根服务器']
            }
            const fileData={
                type:"server",
                fileNameList:{
                    fileName:"hosts-root-all.ini",
                    fileV6Name:"hosts-root-all-v6.ini",
                    whiteFileName:"whiteList-root.ini",
                    whiteV6FileName:"whiteList-root-v6.ini",
                    specialFileName:"hosts-root-special-all.ini",
                    specialV6FileName:"hosts-root-special-all-v6.ini",
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