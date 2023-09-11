import request from "./request";

export const getDNSInfos = (params) => {
    return request({
        url: "/dnsInfos",
        params,
    });
};

export const getWWWInfos = () => {
    return request({
        url: "/wwwInfos",
    });
};

export const getSOAInfos = () => {
    return request({
        url: "/soaInfos",
    });
};

export const getJSONInfos = () => {
    return request({
        url: "/jsonInfos",
    });
};

export const getDebugInfos = () => {
    return request({
        url: "/debugInfos",
    });
};

export const changeDNSInfoState = (uid, type) => {
    return request({
        url: `/dnsInfos/${uid}/state/${type}`,
        method: "put",
    });
};

export const genFiles = (data) => {
    return request({
        url: "/genFiles",
        method: "post",
        data,
    });
};

export const testWWWPage = (data) => {
    return request({
        url: "/testPage",
        method: "post",
        data,
    });
};

export const testDNS = (data) => {
    return request({
        url: "/testDNS",
        method: "post",
        data,
    });
};

export const genJson = (data) => {
    return request({
        url: "/genJson",
        method: "post",
        data,
    });
};

export const publishFiles = (type) => {
    return request({
        url: `/publish/${type}`,
        method: "put",
    });
};

export const addRoot = (data) => {
    return request({
        url: "/roots",
        method: "post",
        data,
    });
};

export const editRoot = (data) => {
    return request({
        url: `/roots/${data.id}`,
        method: "put",
        data,
    });
};

export const deleteRoot = (ids) => {
    return request({
        url: `/roots/${ids}`,
        method: "delete",
    });
};
