##### Signed by https://keybase.io/sam_
```
-----BEGIN PGP SIGNATURE-----
Version: GnuPG v1

iQEcBAABAgAGBQJVn/p8AAoJEIqNy0RwlWfpodUH/3Y8S36SF1t5QmdSfhzB4VOJ
+H2NT+E6NJWduFX1xmv5myzgZK3YwRqVPA6sDR6NmVTPGUcC6QzwI3hdAQKTvGtk
kxNanIAW+FbWv89OV9XV6JBLBH8ji/BfA+AH6/qxFCJwo3HuRQwzROIOEDJizb54
OT4bDRD8sNymHDemk8aEal0W1f8ZOWbXZV8TMSn1r/zyeEWq4/2OArEanIeSa036
4e8GECmAs6SraPrbFkoF5Fmrgg+H083Pz75vcL/T+3JZoxknCyEy3hIXIVMIW1Ks
QU5tN+x9UYuY7o1hsXqFFQ8LoT5OcBQv5zaCj5v5APr/AgILg9tt4K8fXAzudgo=
=5Se4
-----END PGP SIGNATURE-----

```

<!-- END SIGNATURES -->

### Begin signed statement 

#### Expect

```
size  exec  file                                       contents                                                        
            ./                                                                                                         
140           .gitignore                               2cc8d958f1fef68957a55674fdc84c1a842dd5b99ac6910b375e4ac367b05557
291           .travis.yml                              7020d8a02d37c480658488b27cb0edd599459281bf3687992b73eb63cbc1869b
1083          LICENSE                                  30537fd3928feed220209e0a814c8d711432d107e477a86f0266e00f854bb189
1351          README.md                                adf2e1e79aa2b92f276e91c3c450588b90e5caeadf4bf2cd1a0e6d127b8cc9fb
967           Vagrantfile                              c350d1b6dd7ffebfd65894d55d8cf69cbd839cca45f6fe7bb89bff4aef63c77d
344           behat.yml.dist                           eba4ce97680c580b53e4b713c876a738a7d7b7bd25bfb127e2409099aa11fee6
              bin/                                                                                                     
944   x         static-review                          68f206ffc584033176bd85c66a7bfe9328a1dc12519db08314259ef56947b050
2834          composer.json                            b7d11eddfd514ae44fb451b6034a2f5e0695cb1b8223745ffcf2786f2be3aefd
964           phpunit.xml.dist                         06b8d740d2ff306e795891a29d58fb275eb54eb681b10b419497cc84542de69f
              src/                                                                                                     
                Adapter/                                                                                               
994               AdapterInterface.php                 3d38b86730aa0f8addb6017464f1462a7b487f8d8cab3a74fca04260ff709dca
1130              AdapterServiceProvider.php           32976392afc5a12db4c8f91eed92655697257a0766caf557627a0a362dc01b5e
1796              FilesystemAdapter.php                e15b4e51fedece2688aaacc0bd901221ccd90d03a4f2c76644cda730f1241e54
2460              GitAdapter.php                       dbe0081095c802d97e78f476c626947db107197da7fce6f652e5ca7656a6d21c
6691            Application.php                        83f8c6ef04207b0acff77f9897e690722b45010da595ae34bda1d3b66a60a316
                Command/                                                                                               
2749              ReviewCommand.php                    fcda89e558e72cfc1f85d73ba984ffc7b76a69151a48ce95c89f78f719ee51c6
                Configuration/                                                                                         
2238              ConsoleConfigurationLoader.php       10dc098f22982527a54d03f56fb5ebd5408aecff19ae8de77b1110b52b550cd3
2414              DefaultConfigurationLoader.php       2f93a5fbed8497f1be6efe2507ec48098d710fdd36f433c9b49a924c8ee4c2e5
2647              FileConfigurationLoader.php          92efe4a4c15d030f6436d93725c25cd7b7047a4ddb70285a5045c2a821e47cad
                File/                                                                                                  
1454              File.php                             f30ca1f2ef0531947c724d410ffd9cb128ae189ad7aad3677d64c18c5593e80b
774               FileInterface.php                    2c7523b2a434f3d9484783993558621613c26490a4f0f39b76702e98ed8ed12a
                Printer/                                                                                               
991               FilePrinterInterface.php             4bde5def0b4b5f934ca6c3a6b61efa090b7e252cb55e8ec3a888c5ed4e22d491
1837              Printer.php                          35e3dd081f028542fb0bd6d871c8f0b64d6ad475d5eef3af8c0a2a4bbb5546e5
                  Progress/                                                                                            
1112                FilePrinter.php                    dd633cd897419ddbec6890904926cb14bf1b6366126cfb736be9b5634293d66c
2227                ResultCollectorPrinter.php         2687b767645e0df5bf4261ae5d6e1dd0fa8b0a59ca4e566f88f0493c4212b0f3
925               ResultCollectorPrinterInterface.php  51e3a3ae2abc3d8e3d055a21a93e4d02193872f4141cbcba678f679ec52783aa
906               ResultPrinterInterface.php           76ef471db89353ac49977d79481a9f317ea0f4634927341ca8f7c60f09faf0d1
                Result/                                                                                                
2043              Result.php                           45daf0a42e988eac04cdcdf1a419cdaa71c10ee049482fb9254184f40c2344b6
2411              ResultBuilder.php                    f7a84f21a75aa4b96a736f66113b63bc00368f271adaa93e493115b8a2f1e2b7
1829              ResultCollector.php                  ab913edff1f79857ec86f83307563d45954f01fddf5cf432dadf44d2260ad27d
                Review/                                                                                                
1439              AbstractReview.php                   2d4cc9091741455a182a83a074532a1b09688e36c870a0974ebcd0dc063e27a3
1588              NoCommitReview.php                   6497eab0337cb28e68968851219dd449e962ba8062e526492336f8d3a92180b8
1054              ReviewInterface.php                  01150adaff7e7e8e974151ba457cb1392c6076b000fa5cad6b920706cc18edad
2313              ReviewService.php                    12caa52165808932d8382565f541e43c60f6c366267b53fc0367ce591ef223dc
951               ReviewSet.php                        f89fe19463023932ce4cd958af4d70d88fda9d4398b65094e81d620529e4765d
37            static-review.yml.dist                   11de36610227aeec8c7b26d3202663bc290e66b9786c9c748db574d455f4182f
              tests/                                                                                                   
                features/                                                                                              
                  application/                                                                                         
2866                configuration.feature              bcd9b747eaa76eec84378f5f035e6d128d879816cde0b5503e9961a6d369008f
1434                help.feature                       8321010b3dd446cc5b8b203b2894730de465a6b38079e7dc7c4d4fe0ab442a6e
152                 profile.feature                    76d07901288f61d42269d66f9512972b1e1d48ed9b2a43eb3b606e7097b65d61
559                 version.feature                    f1b662abdfc300799832d1b1635bbffe45e570261b8426edb334b580db7bb124
                  drivers/                                                                                             
140                 git_driver.feature                 b94c721defefd945a879804c8ebb9304b317961bed25ce7055e7c13cd1be1fa2
147                 local_driver.feature               99da9cafd8399bf59998793c8798f8877d0ef8f75df56315ed559c128fb02e3f
                  reviews/                                                                                             
852                 nocommit_review.feature            c58312b230d17dbde448b068e0bc902de3cd84efe7dc837f94a98ffba2f6e0ff
                src/                                                                                                   
                  Application/                                                                                         
3879                ApplicationTester.php              4233dcbf189338ec3ef06376f032a1c5a46e2895d9d19a74e2cec8cbc3bdb934
                  Context/                                                                                             
4052                ApplicationContext.php             eb355d7fbb727b744372655a13846b83c9d81d5d033addc30a39d2e0dad4884b
2536                FilesystemContext.php              09736053a082414db4258b7222e0d20e8316b4e972c631dba74a81a2e6029908
                  Review/                                                                                              
958                 PassReview.php                     963e64a901daceea6559d02a3a7205d9dedf1eca642d403c0a39d15cfac16b1f
                unit/                                                                                                  
```

#### Ignore

```
/SIGNED.md
```

#### Presets

```
git      # ignore .git and anything as described by .gitignore files
dropbox  # ignore .dropbox-cache and other Dropbox-related files    
kb       # ignore anything as described by .kbignore files          
```

<!-- summarize version = 0.0.9 -->

### End signed statement

<hr>

#### Notes

With keybase you can sign any directory's contents, whether it's a git repo,
source code distribution, or a personal documents folder. It aims to replace the drudgery of:

  1. comparing a zipped file to a detached statement
  2. downloading a public key
  3. confirming it is in fact the author's by reviewing public statements they've made, using it

All in one simple command:

```bash
keybase dir verify
```

There are lots of options, including assertions for automating your checks.

For more info, check out https://keybase.io/docs/command_line/code_signing