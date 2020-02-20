import { FilterSearchProjects } from './_filter-search.js';

const $originURL = window.location.origin; // console.log($originURL);
const $projectTypeURL = "/projects/type/";
const $projectsCategoryURL = "/projects/category/";
const $source = "/search-result";
const $search = "/projects/project/";

FilterSearchProjects($originURL, $projectTypeURL, $projectsCategoryURL, $source, $search);