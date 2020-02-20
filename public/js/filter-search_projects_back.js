import { FilterSearchProjects } from './_filter-search.js';

const $originURL = window.location.origin; // console.log($originURL);
const $projectTypeURL = "/admin/project/type/";
const $projectsCategoryURL = "/admin/project/category/";
const $source = "/search-result";
const $search = "/admin/project/id/";

FilterSearchProjects($originURL, $projectTypeURL, $projectsCategoryURL, $source, $search);