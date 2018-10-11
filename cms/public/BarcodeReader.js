1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
62
63
64
65
66
67
68
69
70
71
72
73
74
75
76
77
78
79
80
81
82
83
84
85
86
87
88
89
90
91
92
93
94
95
96
97
98
99
100
101
102
103
104
105
106
107
108
109
110
111
112
113
114
115
116
117
118
119
120
121
122
123
124
125
126
127
128
129
130
131
132
133
134
135
136
137
138
139
140
141
142
143
144
145
146
147
148
149
150
151
152
153
154
155
156
157
158
159
160
161
162
163
164
165
166
167
168
169
170
171
172
173
174
175
176
177
178
179
180
181
182
183
184
185
186
187
188
189
190
191
192
193
194
195
196
197
198
199
200
201
202
203
204
205
206
207
208
209
210
211
212
213
214
215
216
217
218
219
220
221
222
223
224
225
226
227
228
229
230
231
232
233
234
235
236
237
238
239
240
241
242
243
244
245
246
247
248
249
250
251
252
253
254
255
256
257
258
259
260
261
262
263
264
265
266
267
268
269
270
271
272
273
274
275
276
277
278
279
280
281
282
283
284
285
286
287
288
289
290
291
292
293
294
295
296
297
298
299
300
301
302
303
304
305
306
307
308
309
310
311
312
313
314
315
316
317
318
319
320
321
322
323
324
325
326
327
328
329
330
331
332
333
334
335
336
337
338
339
340
341
342
343
344
345
346
347
348
349
350
351
352
353
354
355
356
357
358
359
360
361
362
363
364
365
366
367
368
369
370
371
372
373
374
375
376
377
378
379
380
381
382
383
384
385
386
387
388
389
390
391
392
393
394
395
396
397
398
399
400
401
402
403
404
405
406
407
408
409
410
411
412
413
414
415
416
417
418
419
420
421
422
423
424
425
426
427
428
429
430
431
432
433
434
435
436
437
438
439
440
441
442
443
444
445
446
447
448
449
450
451
452
453
454
455
456
457
458
459
460
461
462
463
464
465
466
467
468
469
470
471
472
473
474
475
476
477
478
479
480
481
482
483
484
485
486
487
488
489
490
491
492
493
494
495
496
497
498
499
500
501
502
503
504
505
506
507
508
509
510
511
512
513
514
515
516
517
518
519
520
521
522
523
524
525
526
527
528
529
530
531
532
533
534
535
536
537
538
539
540
541
542
543
544
545
546
547
548
549
550
551
552
553
554
555
556
557
558
559
560
561
562
563
564
565
566
567
568
569
570
571
572
573
574
575
576
577
578
579
580
581
582
583
584
585
586
587
588
589
590
591
592
593
594
595
596
597
598
599
600
601
602
603
604
605
606
607
608
609
610
611
612
613
614
615
616
617
618
619
620
621
622
623
624
625
626
627
628
629
630
631
632
633
634
635
636
637
638
639
640
641
642
643
644
645
646
647
648
649
650
651
652
653
654
655
656
657
658
659
660
661
662
663
664
665
666
667
668
669
670
671
672
673
674
675
676
677
678
679
680
681
682
683
684
685
686
687
688
689
690
691
692
693
694
695
696
697
698
699
700
701
702
703
704
705
706
707
708
709
710
711
712
713
714
715
716
717
718
719
720
721
722
723
724
725
726
727
728
729
730
731
732
733
734
735
736
737
738
739
740
741
742
743
744
745
746
747
748
749
750
751
752
753
754
755
756
757
758
759
760
761
762
763
764
765
766
767
768
769
770
771
772
773
774
775
776
777
778
779
780
781
782
783
784
785
786
787
788
789
790
791
792
793
794
795
796
797
798
799
800
801
802
803
804
805
806
807
808
809
810
811
812
813
814
815
816
817
818
819
820
821
822
823
824
825
826
827
828
829
830
831
832
833
834
835
836
837
838
839
840
841
842
843
844
845
846
847
848
849
850
851
852
853
854
855
856
857
858
859
860
861
862
863
864
865
866
867
868
869
870
871
872
873
874
875
876
877
878
879
880
881
882
883
884
885
886
887
888
889
890
891
892
893
894
895
896
897
898
899
900
901
902
903
904
905
906
907
908
909
910
911
912
913
914
915
916
917
918
919
920
921
922
923
924
925
926
927
928
929
930
931
932
933
934
935
936
937
938
939
940
941
942
943
944
945
946
947
948
949
950
951
952
953
954
955
956
957
958
959
960
961
962
963
964
965
966
967
968
969
970
971
972
973
974
975
976
977
978
979
980
981
982
983
984
985
986
987
988
989
990
991
992
993
994
995
996
997
998
999
1000
1001
1002
1003
1004
1005
1006
1007
1008
1009
1010
1011
1012
1013
1014
1015
1016
1017
1018
1019
1020
1021
1022
1023
1024
1025
1026
1027
1028
1029
1030
1031
1032
1033
1034
1035
1036
1037
1038
1039
1040
1041
1042
1043
1044
1045
1046
1047
1048
1049
1050
1051
1052
1053
1054
1055
1056
1057
1058
1059
1060
1061
1062
1063
1064
1065
1066
1067
1068
1069
1070
1071
1072
1073
1074
1075
1076
1077
1078
1079
1080
1081
1082
1083
1084
1085
1086
1087
1088
1089
1090
1091
1092
1093
1094
1095
1096
1097
1098
1099
1100
1101
1102
1103
1104
1105
1106
1107
1108
1109
1110
1111
1112
1113
1114
1115
1116
1117
1118
1119
1120
1121
1122
1123
1124
1125
1126
1127
1128
1129
1130
1131
1132
1133
1134
1135
1136
1137
1138
1139
1140
1141
1142
1143
1144
1145
1146
1147
1148
1149
1150
1151
1152
1153
1154
1155
1156
1157
1158
1159
1160
1161
1162
1163
1164
1165
1166
1167
1168
1169
1170
1171
1172
1173
1174
1175
1176
1177
1178
1179
1180
1181
1182
1183
1184
1185
1186
1187
1188
1189
1190
1191
1192
1193
1194
1195
1196
1197
1198
1199
1200
1201
1202
1203
1204
1205
1206
1207
1208
1209
1210
1211
1212
1213
1214
1215
1216
1217
1218
1219
1220
1221
1222
1223
1224
1225
1226
1227
1228
1229
1230
1231
1232
1233
1234
1235
1236
1237
1238
1239
1240
1241
1242
1243
1244
1245
1246
1247
1248
1249
1250
1251
1252
1253
1254
1255
1256
1257
1258
1259
1260
1261
1262
1263
1264
1265
1266
1267
1268
1269
1270
1271
1272
1273
1274
1275
1276
1277
1278
1279
1280
1281
1282
1283
1284
1285
1286
1287
1288
1289
1290
1291
1292
1293
1294
1295
1296
1297
1298
1299
1300
1301
1302
1303
1304
1305
1306
1307
1308
1309
1310
1311
1312
1313
1314
1315
1316
1317
1318
1319
1320
1321
1322
1323
1324
1325
1326
1327
1328
1329
1330
1331
1332
1333
1334
1335
1336
1337
1338
1339
1340
1341
1342
1343
1344
1345
1346
1347
1348
1349
1350
1351
1352
1353
1354
1355
1356
1357
1358
1359
1360
1361
1362
1363
1364
1365
1366
1367
1368
1369
1370
1371
1372
1373
1374
1375
1376
1377
1378
1379
1380
1381
1382
1383
1384
1385
1386
1387
1388
1389
1390
1391
1392
1393
1394
1395
1396
1397
1398
1399
1400
1401
1402
1403
1404
1405
1406
1407
1408
1409
1410
1411
1412
1413
1414
1415
1416
1417
1418
1419
1420
1421
1422
1423
1424
1425
1426
1427
1428
1429
1430
1431
1432
1433
1434
1435
1436
1437
1438
1439
1440
1441
1442
1443
1444
1445
1446
1447
1448
1449
1450
1451
1452
1453
1454
1455
1456
1457
1458
1459
1460
1461
1462
1463
1464
1465
1466
1467
1468
1469
1470
1471
1472
1473
1474
1475
1476
1477
1478
1479
1480
1481
1482
1483
1484
1485
1486
1487
1488
1489
1490
1491
1492
1493
1494
1495
1496
1497
1498
1499
1500
1501
1502
1503
1504
1505
1506
1507
1508
1509
1510
1511
1512
1513
1514
1515
1516
1517
1518
1519
1520
1521
1522
1523
1524
1525
1526
1527
1528
1529
1530
1531
1532
1533
1534
1535
1536
1537
1538
1539
1540
1541
1542
1543
1544
1545
1546
1547
1548
1549
1550
1551
1552
1553
1554
1555
1556
1557
1558
1559
1560
1561
1562
1563
1564
1565
1566
1567
1568
1569
1570
1571
1572
1573
1574
1575
1576
1577
1578
1579
1580
1581
1582
1583
1584
1585
1586
1587
1588
1589
1590
1591
1592
1593
1594
1595
1596
1597
1598
1599
1600
1601
1602
1603
1604
1605
1606
1607
1608
1609
1610
1611
1612
1613
1614
1615
1616
1617
1618
1619
1620
1621
1622
1623
1624
1625
1626
1627
1628
1629
1630
1631
1632
1633
1634
1635
1636
1637
1638
1639
1640
1641
1642
1643
1644
1645
1646
1647
1648
1649
1650
1651
1652
1653
1654
1655
1656
1657
1658
1659
1660
1661
1662
1663
1664
1665
1666
1667
1668
1669
1670
1671
1672
1673
1674
1675
1676
1677
1678
1679
1680
1681
1682
1683
1684
1685
1686
1687
1688
1689
1690
1691
1692
1693
1694
1695
1696
1697
1698
1699
1700
1701
1702
1703
1704
1705
1706
1707
1708
1709
1710
1711
1712
1713
1714
1715
1716
1717
1718
1719
1720
1721
1722
1723
1724
1725
1726
1727
1728
1729
1730
1731
1732
1733
1734
1735
1736
1737
1738
1739
1740
1741
1742
1743
1744
1745
1746
1747
1748
1749
1750
1751
1752
1753
1754
1755
1756
1757
1758
1759
1760
1761
1762
1763
1764
1765
1766
1767
1768
1769
1770
1771
1772
1773
1774
1775
1776
1777
1778
1779
1780
1781
1782
1783
1784
1785
1786
1787
1788
1789
1790
1791
1792
1793
1794
1795
1796
1797
1798
1799
1800
1801
1802
1803
1804
1805
1806
1807
1808
1809
1810
1811
1812
1813
1814
1815
1816
1817
1818
1819
1820
1821
1822
1823
1824
1825
1826
1827
1828
1829
1830
1831
1832
1833
1834
1835
1836
1837
1838
1839
1840
1841
1842
1843
1844
1845
1846
1847
1848
1849
1850
1851
1852
1853
1854
1855
1856
1857
1858
1859
1860
1861
1862
1863
1864
1865
1866
1867
1868
1869
1870
1871
1872
1873
1874
1875
1876
1877
1878
1879
1880
1881
1882
1883
1884
1885
1886
1887
1888
1889
1890
1891
1892
1893
1894
1895
1896
1897
1898
1899
1900
1901
1902
1903
1904
1905
1906
1907
1908
1909
1910
1911
1912
1913
1914
1915
1916
1917
1918
1919
1920
1921
1922
1923
1924
1925
1926
1927
1928
1929
1930
1931
1932
1933
1934
1935
1936
1937
1938
1939
1940
1941
1942
1943
1944
1945
1946
1947
1948
1949
1950
1951
1952
1953
1954
1955
1956
1957
1958
1959
1960
1961
1962
1963
1964
1965
1966
1967
1968
1969
1970
1971
1972
1973
1974
1975
1976
1977
1978
1979
1980
1981
1982
1983
1984
1985
1986
1987
1988
1989
1990
1991
1992
1993
1994
1995
1996
1997
1998
1999
2000
2001
2002
2003
2004
2005
2006
2007
2008
2009
2010
2011
2012
2013
2014
2015
2016
2017
2018
2019
2020
2021
2022
2023
2024
2025
2026
2027
2028
2029
2030
2031
2032
2033
2034
2035
2036
2037
2038
2039
2040
2041
2042
2043
2044
2045
2046
2047
2048
2049
2050
2051
2052
2053
2054
2055
2056
2057
2058
2059
2060
2061
2062
2063
2064
2065
2066
2067
2068
2069
2070
2071
2072
2073
2074
2075
2076
2077
2078
2079
2080
2081
2082
2083
2084
2085
2086
2087
2088
2089
2090
2091
2092
2093
2094
2095
2096
2097
2098
2099
2100
2101
2102
2103
2104
2105
2106
2107
2108
2109
2110
2111
2112
2113
2114
2115
2116
2117
2118
2119
2120
2121
2122
2123
2124
2125
2126
2127
2128
2129
2130
2131
2132
2133
2134
2135
2136
2137
2138
2139
2140
2141
2142
2143
2144
2145
2146
2147
2148
2149
2150
2151
2152
2153
2154
2155
2156
2157
2158
2159
2160
2161
2162
2163
2164
2165
2166
2167
2168
2169
2170
2171
2172
2173
2174
2175
2176
2177
2178
2179
2180
2181
2182
2183
2184
2185
2186
2187
2188
2189
2190
2191
2192
2193
2194
2195
2196
2197
2198
2199
2200
2201
2202
2203
2204
2205
2206
2207
2208
2209
2210
2211
2212
2213
2214
2215
2216
2217
2218
2219
2220
2221
2222
2223
2224
2225
2226
2227
2228
2229
2230
2231
2232
2233
2234
2235
2236
2237
2238
2239
2240
2241
2242
2243
2244
2245
2246
2247
2248
2249
2250
2251
2252
2253
2254
2255
2256
2257
2258
2259
2260
2261
2262
2263
2264
2265
2266
2267
2268
2269
2270
2271
2272
2273
2274
2275
2276
2277
2278
2279
2280
2281
2282
2283
2284
2285
2286
2287
2288
2289
2290
2291
2292
2293
2294
2295
2296
2297
2298
2299
2300
2301
2302
2303
2304
2305
2306
2307
2308
2309
2310
2311
2312
2313
2314
2315
2316
2317
2318
2319
2320
2321
2322
2323
2324
2325
2326
2327
2328
2329
2330
2331
2332
2333
2334
2335
2336
2337
2338
2339
2340
2341
2342
2343
2344
2345
2346
2347
2348
2349
2350
2351
2352
2353
2354
2355
2356
2357
2358
2359
2360
2361
2362
2363
2364
2365
2366
2367
2368
2369
2370
2371
2372
2373
2374
2375
2376
2377
2378
2379
2380
2381
2382
2383
2384
2385
2386
2387
2388
2389
2390
2391
2392
2393
2394
2395
2396
2397
2398
2399
2400
2401
2402
2403
2404
2405
2406
2407
2408
2409
2410
2411
2412
2413
2414
2415
2416
2417
2418
2419
2420
2421
2422
2423
2424
2425
2426
2427
2428
2429
2430
2431
2432
2433
2434
2435
2436
2437
2438
2439
2440
2441
2442
2443
2444
2445
2446
2447
2448
2449
2450
2451
2452
2453
2454
2455
2456
2457
2458
2459
2460
2461
2462
2463
2464
2465
2466
2467
2468
2469
2470
2471
2472
2473
2474
2475
2476
2477
2478
2479
2480
2481
2482
2483
2484
2485
2486
2487
2488
2489
2490
2491
2492
2493
2494
2495
2496
2497
2498
2499
2500
2501
2502
2503
2504
2505
2506
2507
2508
2509
2510
2511
2512
2513
2514
2515
2516
2517
2518
2519
2520
2521
2522
2523
2524
2525
2526
2527
2528
2529
2530
2531
2532
2533
2534
2535
2536
2537
2538
2539
2540
2541
2542
2543
2544
2545
2546
2547
2548
2549
2550
2551
2552
2553
2554
2555
2556
2557
2558
2559
2560
2561
2562
2563
2564
2565
2566
2567
2568
2569
2570
2571
2572
2573
2574
2575
2576
2577
2578
2579
2580
2581
2582
2583
2584
2585
2586
2587
2588
2589
2590
2591
2592
2593
2594
2595
2596
2597
2598
2599
2600
2601
2602
2603
2604
2605
2606
2607
2608
2609
2610
2611
2612
2613
2614
2615
2616
2617
2618
2619
2620
2621
2622
2623
2624
2625
2626
2627
2628
2629
2630
2631
2632
2633
2634
2635
2636
2637
2638
2639
2640
2641
2642
2643
2644
2645
2646
2647
2648
2649
2650
2651
2652
2653
2654
2655
2656
2657
2658
2659
2660
2661
2662
2663
2664
2665
2666
2667
2668
2669
2670
2671
2672
2673
2674
2675
2676
2677
2678
2679
2680
2681
2682
2683
2684
2685
2686
2687
2688
2689
2690
2691
2692
2693
2694
2695
2696
2697
2698
2699
2700
2701
2702
2703
2704
2705
2706
2707
2708
2709
2710
2711
2712
2713
2714
2715
2716
2717
2718
2719
2720
2721
2722
2723
2724
2725
2726
2727
2728
2729
2730
2731
2732
2733
2734
2735
2736
2737
2738
2739
2740
2741
2742
2743
2744
2745
2746
2747
2748
2749
2750
2751
2752
2753
2754
2755
2756
2757
2758
2759
2760
2761
2762
2763
2764
2765
2766
2767
2768
2769
2770
2771
2772
2773
2774
2775
2776
2777
2778
2779
2780
2781
2782
2783
2784
2785
2786
2787
2788
2789
2790
2791
2792
2793
2794
2795
2796
2797
2798
2799
2800
2801
2802
2803
2804
2805
2806
2807
2808
2809
2810
2811
2812
2813
2814
2815
2816
2817
2818
2819
2820
2821
2822
2823
2824
2825
2826
2827
2828
2829
2830
2831
2832
2833
2834
2835
2836
2837
2838
2839
2840
2841
2842
2843
2844
2845
2846
2847
2848
2849
2850
2851
2852
2853
2854
2855
2856
2857
2858
2859
2860
2861
2862
2863
2864
2865
2866
2867
2868
2869
2870
2871
2872
2873
2874
2875
2876
2877
2878
2879
2880
2881
2882
2883
2884
2885
2886
2887
2888
2889
2890
2891
2892
2893
2894
2895
2896
2897
2898
2899
2900
2901
2902
2903
2904
2905
2906
2907
2908
2909
2910
2911
2912
2913
2914
2915
2916
2917
2918
2919
2920
2921
2922
2923
2924
2925
2926
2927
2928
2929
2930
2931
2932
2933
2934
2935
2936
2937
2938
2939
2940
2941
2942
2943
2944
2945
2946
2947
2948
2949
2950
2951
2952
2953
2954
2955
2956
2957
2958
2959
2960
2961
2962
2963
2964
2965
2966
2967
2968
2969
2970
2971
2972
2973
2974
2975
2976
2977
2978
2979
2980
2981
2982
2983
2984
2985
2986
2987
2988
2989
2990
2991
2992
2993
2994
2995
2996
2997
2998
2999
3000
3001
3002
3003
3004
3005
3006
3007
3008
3009
3010
3011
3012
3013
3014
3015
3016
3017
3018
3019
3020
3021
3022
3023
3024
3025
3026
3027
3028
3029
3030
3031
3032
3033
3034
3035
3036
3037
3038
3039
3040
3041
3042
3043
3044
3045
3046
3047
3048
3049
3050
3051
3052
3053
3054
3055
3056
3057
3058
3059
3060
3061
3062
3063
3064
3065
3066
3067
3068
3069
3070
3071
3072
3073
3074
3075
3076
3077
3078
3079
3080
3081
3082
3083
3084
3085
3086
3087
3088
3089
3090
3091
3092
3093
3094
3095
3096
3097
3098
3099
3100
3101
3102
3103
3104
3105
3106
3107
3108
3109
3110
3111
3112
3113
3114
3115
3116
3117
3118
3119
3120
3121
3122
3123
3124
3125
3126
3127
3128
3129
3130
3131
3132
3133
3134
3135
3136
3137
3138
3139
3140
3141
3142
3143
3144
3145
3146
3147
3148
3149
3150
3151
3152
3153
3154
3155
3156
3157
3158
3159
3160
3161
3162
3163
3164
3165
3166
3167
3168
3169
3170
3171
3172
3173
3174
3175
3176
3177
3178
3179
3180
3181
3182
3183
3184
3185
3186
3187
3188
3189
3190
3191
3192
3193
3194
3195
3196
3197
3198
3199
3200
3201
3202
3203
3204
3205
3206
3207
3208
3209
3210
3211
3212
3213
3214
3215
3216
3217
3218
3219
3220
3221
3222
3223
3224
3225
3226
3227
3228
3229
3230
3231
3232
3233
3234
3235
3236
3237
3238
3239
3240
3241
3242
3243
3244
3245
3246
3247
3248
3249
3250
3251
3252
3253
3254
3255
3256
3257
3258
3259
3260
3261
3262
3263
3264
3265
3266
3267
3268
3269
3270
3271
3272
3273
3274
3275
3276
3277
3278
3279
3280
3281
3282
3283
3284
3285
3286
3287
3288
3289
3290
3291
3292
3293
3294
3295
3296
3297
3298
3299
3300
3301
3302
3303
3304
3305
3306
3307
3308
3309
3310
3311
3312
3313
3314
3315
3316
3317
3318
3319
3320
3321
3322
3323
3324
3325
3326
3327
3328
3329
3330
3331
3332
3333
3334
3335
3336
3337
3338
3339
3340
3341
3342
3343
3344
3345
3346
3347
3348
3349
3350
3351
3352
3353
3354
3355
3356
3357
3358
3359
3360
3361
3362
3363
3364
3365
3366
3367
3368
3369
3370
3371
3372
3373
3374
3375
3376
3377
3378
3379
3380
3381
3382
3383
3384
3385
3386
3387
3388
3389
3390
3391
3392
3393
3394
3395
3396
3397
3398
3399
3400
3401
3402
3403
3404
3405
3406
3407
3408
3409
3410
3411
3412
3413
3414
3415
3416
3417
3418
3419
3420
3421
3422
3423
3424
3425
3426
3427
3428
3429
3430
3431
3432
3433
3434
3435
3436
3437
3438
3439
3440
3441
3442
3443
3444
3445
3446
3447
3448
3449
3450
3451
3452
3453
3454
3455
3456
3457
3458
3459
3460
3461
3462
3463
3464
3465
3466
3467
3468
3469
3470
3471
3472
3473
3474
3475
3476
3477
3478
3479
3480
3481
3482
3483
3484
3485
3486
3487
3488
3489
3490
3491
3492
3493
3494
3495
3496
3497
3498
3499
3500
3501
3502
3503
3504
3505
3506
3507
3508
3509
3510
3511
3512
3513
3514
3515
3516
3517
3518
3519
3520
3521
3522
3523
3524
3525
3526
3527
3528
3529
3530
3531
3532
3533
3534
3535
3536
3537
3538
3539
3540
3541
3542
3543
3544
3545
3546
3547
3548
3549
3550
3551
3552
3553
3554
3555
3556
3557
3558
3559
3560
3561
3562
3563
3564
3565
3566
3567
3568
3569
3570
3571
3572
3573
3574
3575
3576
3577
3578
3579
3580
3581
3582
3583
3584
3585
3586
3587
3588
3589
3590
3591
3592
3593
3594
3595
3596
3597
3598
3599
3600
3601
3602
3603
3604
3605
3606
3607
3608
3609
3610
3611
3612
3613
3614
3615
3616
3617
3618
3619
3620
3621
3622
3623
3624
3625
3626
3627
3628
3629
3630
3631
3632
3633
3634
3635
3636
3637
3638
3639
3640
3641
3642
3643
3644
3645
3646
3647
3648
3649
3650
3651
3652
3653
3654
3655
3656
3657
3658
3659
3660
3661
3662
3663
3664
3665
3666
3667
3668
3669
3670
3671
3672
3673
3674
3675
3676
3677
3678
3679
3680
3681
3682
3683
3684
3685
3686
3687
3688
3689
3690
3691
3692
3693
3694
3695
3696
3697
3698
3699
3700
3701
3702
3703
3704
3705
3706
3707
3708
3709
3710
3711
3712
3713
3714
3715
3716
3717
3718
3719
3720
3721
3722
3723
3724
3725
3726
3727
3728
3729
3730
3731
3732
3733
3734
3735
3736
3737
3738
3739
3740
3741
3742
3743
3744
3745
3746
3747
3748
3749
3750
3751
3752
3753
3754
3755
3756
3757
3758
3759
3760
3761
3762
3763
3764
3765
3766
3767
3768
3769
3770
3771
3772
3773
3774
3775
3776
3777
3778
3779
3780
3781
3782
3783
3784
3785
3786
3787
3788
3789
3790
3791
3792
3793
3794
3795
3796
3797
3798
3799
3800
3801
3802
3803
3804
3805
3806
3807
3808
3809
3810
3811
3812
3813
3814
3815
3816
3817
3818
3819
3820
3821
3822
3823
3824
3825
3826
3827
3828
3829
3830
3831
3832
3833
3834
3835
3836
3837
3838
3839
3840
3841
3842
3843
3844
3845
3846
3847
3848
3849
3850
3851
3852
3853
3854
3855
3856
3857
3858
3859
3860
3861
3862
3863
3864
3865
3866
3867
3868
3869
3870
3871
3872
3873
3874
3875
3876
3877
3878
3879
3880
3881
3882
3883
3884
3885
3886
3887
3888
3889
3890
3891
3892
3893
3894
3895
3896
3897
3898
3899
3900
3901
3902
3903
3904
3905
3906
3907
3908
3909
3910
3911
3912
3913
3914
3915
3916
3917
3918
3919
3920
3921
3922
3923
3924
3925
3926
3927
3928
3929
3930
3931
3932
3933
3934
3935
3936
3937
3938
3939
3940
3941
3942
3943
3944
3945
3946
3947
3948
3949
3950
3951
3952
3953
3954
3955
3956
3957
3958
3959
3960
3961
3962
3963
3964
3965
3966
3967
3968
3969
3970
3971
3972
3973
3974
3975
3976
3977
3978
3979
3980
3981
3982
3983
3984
3985
3986
3987
3988
3989
3990
3991
3992
3993
3994
3995
3996
3997
3998
3999
4000
4001
4002
4003
4004
4005
4006
4007
4008
4009
4010
4011
4012
4013
4014
4015
4016
4017
4018
4019
4020
4021
4022
4023
4024
4025
4026
4027
4028
4029
4030
4031
4032
4033
4034
4035
4036
4037
4038
4039
4040
4041
4042
4043
4044
4045
4046
4047
4048
4049
4050
4051
4052
4053
4054
4055
4056
4057
4058
4059
4060
4061
4062
4063
4064
4065
4066
4067
4068
4069
4070
4071
4072
4073
4074
4075
4076
4077
4078
4079
4080
4081
4082
4083
4084
4085
4086
4087
4088
4089
4090
4091
4092
4093
4094
4095
4096
4097
4098
4099
4100
4101
4102
4103
4104
4105
4106
4107
4108
4109
4110
4111
4112
4113
4114
4115
4116
4117
4118
4119
4120
4121
4122
4123
4124
4125
4126
4127
4128
4129
4130
4131
4132
4133
4134
(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
/**
 * CallBacks:
 * __________________________________________________________________________________
 * All the callback function should have one parameter:
 * function(result){};
 * And the result parameter will contain an array of objects that look like BarcodeReader.
 * result = [{Format: the barcode type, Value: the value of the barcode}];
 * __________________________________________________________________________________
 *
 * You can use either the set functions or just access the properties directly to set callback or
 * other properties. Just always remember to call Init() before starting to decode something never mess
 * around with the SupportedFormats property.
 *
 */
 
var EXIF = require('./exif');
var decoderWorkerBlobString = require('./DecoderWorker');
 
var BarcodeReader = {
  Config: {
    // Set to false if the decoder should look for one barcode and then stop. Increases performance.
    Multiple: true,
 
    // The formats that the decoder will look for.
    DecodeFormats: ["Code128", "Code93", "Code39", "EAN-13", "2Of5", "Inter2Of5", "Codabar"],
 
    // ForceUnique just must makes sure that the callback function isn't repeatedly called
    // with the same barcode. Especially in the case of a video stream.
    ForceUnique: true,
 
    // Set to true if information about the localization should be recieved from the worker.
    LocalizationFeedback: false,
 
    // Set to true if checking orientation of the image should be skipped.
    // Checking orientation takes a bit of time for larger images, so if
    // you are sure that the image orientation is 1 you should skip it.
    SkipOrientation: false
  },
  SupportedFormats: ["Code128", "Code93", "Code39", "EAN-13", "2Of5", "Inter2Of5", "Codabar"], // Don't touch.
  ScanCanvas: null, // Don't touch the canvas either.
  ScanContext: null,
  SquashCanvas: document.createElement("canvas"),
  ImageCallback: null, // Callback for the decoding of an image.
  StreamCallback: null, // Callback for the decoding of a video.
  LocalizationCallback: null, // Callback for localization.
  ImageErrorCallback: null, // Callback for error on image loading.
  Stream: null, // The actual video.
  DecodeStreamActive: false, // Will be set to false when StopStreamDecode() is called.
  Decoded: [], // Used to enfore the ForceUnique property.
  DecoderWorker: new Worker( URL.createObjectURL(new Blob([decoderWorkerBlobString], {type: "application/javascript"}) ) ),
  OrientationCallback: null,
  // Always call the Init().
  Init: function() {
    BarcodeReader.ScanCanvas = BarcodeReader.FixCanvas(document.createElement("canvas"));
    BarcodeReader.ScanCanvas.width = 640;
    BarcodeReader.ScanCanvas.height = 480;
    BarcodeReader.ScanContext = BarcodeReader.ScanCanvas.getContext("2d");
  },
 
  // Value should be true or false.
  SetRotationSkip: function(value) {
    BarcodeReader.Config.SkipOrientation = value;
  },
  // Sets the callback function for the image decoding.
  SetImageCallback: function(callBack) {
    BarcodeReader.ImageCallback = callBack;
  },
 
  // Sets the callback function for the video decoding.
  SetStreamCallback: function(callBack) {
    BarcodeReader.StreamCallback = callBack;
  },
 
  // Sets callback for localization, the callback function should take one argument.
  // This will be an array with objects with format.
  // {x, y, width, height}
  // This represents a localization rectangle.
  // The rectangle comes from a 320, 240 area i.e the search canvas.
  SetLocalizationCallback: function(callBack) {
    BarcodeReader.LocalizationCallback = callBack;
    BarcodeReader.Config.LocalizationFeedback = true;
  },
 
  // Sets the callback function when loading a wrong image.
  SetImageErrorCallback: function(callBack) {
    BarcodeReader.ImageErrorCallback = callBack;
  },
 
  // Set to true if LocalizationCallback is set and you would like to
  // receive the feedback or false if
  SwitchLocalizationFeedback: function(bool) {
    BarcodeReader.Config.LocalizationFeedback = bool;
  },
 
  // Switches for changing the Multiple property.
  DecodeSingleBarcode: function() {
    BarcodeReader.Config.Multiple = false;
  },
  DecodeMultiple: function() {
    BarcodeReader.Config.Multiple = true;
  },
 
  // Sets the formats to decode, formats should be an array of a subset of the supported formats.
  SetDecodeFormats: function(formats) {
    BarcodeReader.Config.DecodeFormats = [];
    for (var i = 0; i < formats.length; i++) {
      if (BarcodeReader.SupportedFormats.indexOf(formats[i]) !== -1) {
        BarcodeReader.Config.DecodeFormats.push(formats[i]);
      }
    }
    if (BarcodeReader.Config.DecodeFormats.length === 0) {
      BarcodeReader.Config.DecodeFormats = BarcodeReader.SupportedFormats.slice();
    }
  },
 
  // Removes a list of formats from the formats to decode.
  SkipFormats: function(formats) {
    for (var i = 0; i < formats.length; i++) {
      var index = BarcodeReader.Config.DecodeFormats.indexOf(formats[i]);
      if (index >= 0) {
        BarcodeReader.Config.DecodeFormats.splice(index, 1);
      }
    }
  },
 
  // Adds a list of formats to the formats to decode.
  AddFormats: function(formats) {
    for (var i = 0; i < formats.length; i++) {
      if (BarcodeReader.SupportedFormats.indexOf(formats[i]) !== -1) {
        if (BarcodeReader.Config.DecodeFormats.indexOf(formats[i]) === -1) {
          BarcodeReader.Config.DecodeFormats.push(formats[i]);
        }
      }
    }
  },
 
  // The callback function for image decoding used internally by BarcodeReader.
  BarcodeReaderImageCallback: function(e) {
    if (e.data.success === "localization") {
      if (BarcodeReader.Config.LocalizationFeedback) {
        BarcodeReader.LocalizationCallback(e.data.result);
      }
      return;
    }
    if (e.data.success === "orientationData") {
      BarcodeReader.OrientationCallback(e.data.result);
      return;
    }
    var filteredData = [];
    for (var i = 0; i < e.data.result.length; i++) {
      if (BarcodeReader.Decoded.indexOf(e.data.result[i].Value) === -1 || BarcodeReader.Config.ForceUnique === false) {
        filteredData.push(e.data.result[i]);
        if (BarcodeReader.Config.ForceUnique) BarcodeReader.Decoded.push(e.data.result[i].Value);
      }
    }
    BarcodeReader.ImageCallback(filteredData);
    BarcodeReader.Decoded = [];
  },
 
  // The callback function for stream decoding used internally by BarcodeReader.
  BarcodeReaderStreamCallback: function(e) {
    if (e.data.success === "localization") {
      if (BarcodeReader.Config.LocalizationFeedback) {
        BarcodeReader.LocalizationCallback(e.data.result);
      }
      return;
    }
    if (e.data.success && BarcodeReader.DecodeStreamActive) {
      var filteredData = [];
      for (var i = 0; i < e.data.result.length; i++) {
        if (BarcodeReader.Decoded.indexOf(e.data.result[i].Value) === -1 || BarcodeReader.ForceUnique === false) {
          filteredData.push(e.data.result[i]);
          if (BarcodeReader.ForceUnique) BarcodeReader.Decoded.push(e.data.result[i].Value);
        }
      }
      if (filteredData.length > 0) {
        BarcodeReader.StreamCallback(filteredData);
      }
    }
    if (BarcodeReader.DecodeStreamActive) {
      BarcodeReader.ScanContext.drawImage(BarcodeReader.Stream, 0, 0, BarcodeReader.ScanCanvas.width, BarcodeReader.ScanCanvas.height);
      BarcodeReader.DecoderWorker.postMessage({
        scan: BarcodeReader.ScanContext.getImageData(0, 0, BarcodeReader.ScanCanvas.width, BarcodeReader.ScanCanvas.height).data,
        scanWidth: BarcodeReader.ScanCanvas.width,
        scanHeight: BarcodeReader.ScanCanvas.height,
        multiple: BarcodeReader.Config.Multiple,
        decodeFormats: BarcodeReader.Config.DecodeFormats,
        cmd: "normal",
        rotation: 1
      });
 
    }
    if (!BarcodeReader.DecodeStreamActive) {
      BarcodeReader.Decoded = [];
    }
  },
 
  // The image decoding function, image is a data source for an image or an image element.
  DecodeImage: function(image) {
    var img = new Image();
    img.onerror = BarcodeReader.ImageErrorCallback;
 
    if (image instanceof Image || image instanceof HTMLImageElement) {
      image.exifdata = false;
      if (image.complete) {
        if (BarcodeReader.Config.SkipOrientation) {
          BarcodeReader.BarcodeReaderDecodeImage(image, 1, "");
        } else {
          EXIF.getData(image, function(exifImage) {
            var orientation = EXIF.getTag(exifImage, "Orientation");
            var sceneType = EXIF.getTag(exifImage, "SceneCaptureType");
            if (typeof orientation !== 'number') orientation = 1;
            BarcodeReader.BarcodeReaderDecodeImage(exifImage, orientation, sceneType);
          });
        }
      } else {
        img.onload = function() {
          if (BarcodeReader.Config.SkipOrientation) {
            BarcodeReader.BarcodeReaderDecodeImage(img, 1, "");
          } else {
            EXIF.getData(this, function(exifImage) {
              var orientation = EXIF.getTag(exifImage, "Orientation");
              var sceneType = EXIF.getTag(exifImage, "SceneCaptureType");
              if (typeof orientation !== 'number') orientation = 1;
              BarcodeReader.BarcodeReaderDecodeImage(exifImage, orientation, sceneType);
            });
          }
        };
        img.src = image.src;
      }
    } else {
      img.onload = function() {
        if (BarcodeReader.Config.SkipOrientation) {
          BarcodeReader.BarcodeReaderDecodeImage(img, 1, "");
        } else {
          EXIF.getData(this, function(exifImage) {
            var orientation = EXIF.getTag(exifImage, "Orientation");
            var sceneType = EXIF.getTag(exifImage, "SceneCaptureType");
            if (typeof orientation !== 'number') orientation = 1;
            BarcodeReader.BarcodeReaderDecodeImage(exifImage, orientation, sceneType);
          });
        }
      };
      img.src = image;
    }
  },
 
  // Starts the decoding of a stream, the stream is a video not a blob i.e it's an element.
  DecodeStream: function(stream) {
    BarcodeReader.Stream = stream;
    BarcodeReader.DecodeStreamActive = true;
    BarcodeReader.DecoderWorker.onmessage = BarcodeReader.BarcodeReaderStreamCallback;
    BarcodeReader.ScanContext.drawImage(stream, 0, 0, BarcodeReader.ScanCanvas.width, BarcodeReader.ScanCanvas.height);
    BarcodeReader.DecoderWorker.postMessage({
      scan: BarcodeReader.ScanContext.getImageData(0, 0, BarcodeReader.ScanCanvas.width, BarcodeReader.ScanCanvas.height).data,
      scanWidth: BarcodeReader.ScanCanvas.width,
      scanHeight: BarcodeReader.ScanCanvas.height,
      multiple: BarcodeReader.Config.Multiple,
      decodeFormats: BarcodeReader.Config.DecodeFormats,
      cmd: "normal",
      rotation: 1
    });
  },
 
  // Stops the decoding of a stream.
  StopStreamDecode: function() {
    BarcodeReader.DecodeStreamActive = false;
    BarcodeReader.Decoded = [];
  },
 
  BarcodeReaderDecodeImage: function(image, orientation, sceneCaptureType) {
    if (orientation === 8 || orientation === 6) {
      if (sceneCaptureType === "Landscape" && image.width > image.height) {
        orientation = 1;
        BarcodeReader.ScanCanvas.width = 640;
        BarcodeReader.ScanCanvas.height = 480;
      } else {
        BarcodeReader.ScanCanvas.width = 480;
        BarcodeReader.ScanCanvas.height = 640;
      }
    } else {
      BarcodeReader.ScanCanvas.width = 640;
      BarcodeReader.ScanCanvas.height = 480;
    }
    BarcodeReader.DecoderWorker.onmessage = BarcodeReader.BarcodeReaderImageCallback;
    BarcodeReader.ScanContext.drawImage(image, 0, 0, BarcodeReader.ScanCanvas.width, BarcodeReader.ScanCanvas.height);
    BarcodeReader.Orientation = orientation;
    BarcodeReader.DecoderWorker.postMessage({
      scan: BarcodeReader.ScanContext.getImageData(0, 0, BarcodeReader.ScanCanvas.width, BarcodeReader.ScanCanvas.height).data,
      scanWidth: BarcodeReader.ScanCanvas.width,
      scanHeight: BarcodeReader.ScanCanvas.height,
      multiple: BarcodeReader.Config.Multiple,
      decodeFormats: BarcodeReader.Config.DecodeFormats,
      cmd: "normal",
      rotation: orientation,
      postOrientation: BarcodeReader.PostOrientation
    });
  },
 
  DetectVerticalSquash: function(img) {
    var ih = img.naturalHeight;
    var canvas = BarcodeReader.SquashCanvas;
    var alpha;
    var data;
    canvas.width = 1;
    canvas.height = ih;
    var ctx = canvas.getContext('2d');
    ctx.drawImage(img, 0, 0);
    try {
      data = ctx.getImageData(0, 0, 1, ih).data;
    } catch (err) {
      console.log("Cannot check verticalSquash: CORS?");
      return 1;
    }
    var sy = 0;
    var ey = ih;
    var py = ih;
    while (py > sy) {
      alpha = data[(py - 1) * 4 + 3];
      if (alpha === 0) {
        ey = py;
      } else {
        sy = py;
      }
      py = (ey + sy) >> 1;
    }
    var ratio = (py / ih);
    return (ratio === 0) ? 1 : ratio;
  },
 
  FixCanvas: function(canvas) {
    var ctx = canvas.getContext('2d');
    var drawImage = ctx.drawImage;
    ctx.drawImage = function(img, sx, sy, sw, sh, dx, dy, dw, dh) {
      var vertSquashRatio = 1;
      if (!!img && img.nodeName === 'IMG') {
        vertSquashRatio = BarcodeReader.DetectVerticalSquash(img);
        // sw || (sw = img.naturalWidth);
        // sh || (sh = img.naturalHeight);
      }
      if (arguments.length === 9)
        drawImage.call(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
      else if (typeof sw !== 'undefined')
        drawImage.call(ctx, img, sx, sy, sw, sh / vertSquashRatio);
      else
        drawImage.call(ctx, img, sx, sy);
    };
    return canvas;
  }
};
 
 
if (typeof exports !== 'undefined') {
  if (typeof module !== 'undefined' && module.exports) {
      exports = module.exports = BarcodeReader;
  }
  exports.BarcodeReader = BarcodeReader;
} else {
  root.BarcodeReader = BarcodeReader;
}
 
},{"./DecoderWorker":2,"./exif":3}],2:[function(require,module,exports){
/* --------------------------------------------------
Javascript Only Barcode_Reader (BarcodeReader) V1.6 by Eddie Larsson <https://github.com/EddieLa/BarcodeReader>
 
This software is provided under the MIT license, http://opensource.org/licenses/MIT.
All use of this software must include this
text, including the reference to the creator of the original source code. The
originator accepts no responsibility of any kind pertaining to
use of this software.
 
Copyright (c) 2013 Eddie Larsson
 
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 
------------------------ */
 
var decoderWorkerBlob = function decoderWorkerBlob(){
 
  function Rotate(data, width, height, rotation) {
    var newData = [];
    var x, y;
    switch (rotation) {
      case 90:
        for (x = 0; x < width * 4; x += 4) {
          for (y = width * 4 * (height - 1); y >= 0; y -= width * 4) {
            newData.push(data[x + y]);
            newData.push(data[x + y + 1]);
            newData.push(data[x + y + 2]);
            newData.push(data[x + y + 3]);
          }
        }
        break;
      case -90:
        for (x = width * 4 - 4; x >= 0; x -= 4) {
          for (y = 0; y < data.length; y += width * 4) {
            newData.push(data[x + y]);
            newData.push(data[x + y + 1]);
            newData.push(data[x + y + 2]);
            newData.push(data[x + y + 3]);
          }
        }
        break;
      case 180:
        for (y = width * 4 * (height - 1); y >= 0; y -= width * 4) {
          for (x = width * 4 - 4; x >= 0; x -= 4) {
            newData.push(data[x + y]);
            newData.push(data[x + y + 1]);
            newData.push(data[x + y + 2]);
            newData.push(data[x + y + 3]);
          }
        }
    }
    return new Uint8ClampedArray(newData);
  }
 
  function BoxFilter(data, width, radius) {
    var elements = [];
    var sum = [];
    var val;
    var x, y, i;
    for (x = 0; x < width; x++) {
      elements.push([]);
      sum.push(0);
      for (y = 0; y < (radius + 1) * width; y += width) {
        elements[elements.length - 1].push(data[x + y]);
        sum[sum.length - 1] = sum[sum.length - 1] + data[x + y];
      }
    }
    var newData = [];
    for (y = 0; y < data.length; y += width) {
      for (x = 0; x < width; x++) {
        var newVal = 0;
        var length = 0;
        for (i = x; i >= 0; i--) {
          newVal += sum[i];
          length++;
          if (length === radius + 1) break;
        }
        var tempLength = 0;
        for (i = x + 1; i < width; i++) {
          newVal += sum[i];
          length++;
          tempLength++;
          if (tempLength === radius) break;
        }
        length *= elements[0].length;
        newVal /= length;
        newData.push(newVal);
      }
      if (y - radius * width >= 0) {
        for (i = 0; i < elements.length; i++) {
          val = elements[i].shift();
          sum[i] = sum[i] - val;
        }
      }
      if (y + (radius + 1) * width < data.length) {
        for (i = 0; i < elements.length; i++) {
          val = data[i + y + (radius + 1) * width];
          elements[i].push(val);
          sum[i] = sum[i] + val;
        }
      }
    }
    return newData;
  }
 
  function Scale(data, width, height) {
    var newData = [];
    var x, y;
    for (y = 0; y < data.length; y += width * 8) {
      for (x = 0; x < width * 4; x += 8) {
        var r = (data[y + x] + data[y + x + 4] + data[y + width * 4 + x] + data[y + width * 4 + x + 4]) / 4;
        newData.push(r);
        var g = (data[y + x + 1] + data[y + x + 4 + 1] + data[y + width * 4 + x + 1] + data[y + width * 4 + x + 4 + 1]) / 4;
        newData.push(g);
        var b = (data[y + x + 2] + data[y + x + 4 + 2] + data[y + width * 4 + x + 2] + data[y + width * 4 + x + 4 + 2]) / 4;
        newData.push(b);
        newData.push(255);
      }
    }
    return new Uint8ClampedArray(newData);
  }
 
  function IntensityGradient(data, width) {
    var newData = [];
    var max = Number.MIN_VALUE;
    var min = Number.MAX_VALUE;
    var x, y, i;
    for (y = 0; y < data.length; y += width * 4) {
      for (x = 0; x < width * 4; x += 4) {
        var horizontalDiff = 0;
        var verticalDiff = 0;
        for (i = 1; i < 2; i++) {
          if (x + i * 4 < width * 4) {
            horizontalDiff = horizontalDiff + Math.abs(data[y + x] - data[y + x + i * 4]);
          }
          if (y + width * 4 * i < data.length) {
            verticalDiff += verticalDiff + Math.abs(data[y + x] - data[y + x + width * 4 * i]);
          }
        }
        var diff = horizontalDiff - verticalDiff;
        max = diff > max ? diff : max;
        min = diff < min ? diff : min;
        newData.push(diff);
      }
    }
    if (min < 0) {
      for (i = 0; i < newData.length; i++) {
        newData[i] = newData[i] - min;
      }
      min = 0;
    }
    return newData;
  }
 
  function greyScale(data) {
    var i;
    for (i = 0; i < data.length; i += 4) {
      var max = 0;
      var min = 255;
      max = data[i] > max ? data[i] : max;
      max = data[i + 1] > max ? data[i + 1] : max;
      max = data[i + 2] > max ? data[i + 2] : max;
      min = data[i] < min ? data[i] : min;
      min = data[i + 1] < min ? data[i + 1] : min;
      min = data[i + 2] < min ? data[i + 2] : min;
      data[i] = data[i + 1] = data[i + 2] = (max + min) / 2;
    }
  }
 
  function histogram(data) {
    var i;
    var hist = [];
    for (i = 0; i < 256; i++) {
      hist[i] = 0;
    }
    for (i = 0; i < data.length; i += 4) {
      hist[data[i]] = hist[data[i]] + 1;
    }
    return hist;
  }
 
  function otsu(histogram, total) {
    var i;
    var sum = 0;
    for (i = 1; i < histogram.length; ++i)
      sum += i * histogram[i];
    var sumB = 0;
    var wB = 0;
    var wF = 0;
    var mB;
    var mF;
    var max = 0.0;
    var between = 0.0;
    var threshold1 = 0.0;
    var threshold2 = 0.0;
    for (i = 0; i < histogram.length; ++i) {
      wB += histogram[i];
      if (wB === 0)
        continue;
      wF = total - wB;
      if (wF === 0)
        break;
      sumB += i * histogram[i];
      mB = sumB / wB;
      mF = (sum - sumB) / wF;
      between = wB * wF * Math.pow(mB - mF, 2);
      if (between >= max) {
        threshold1 = i;
        if (between > max) {
          threshold2 = i;
        }
        max = between;
      }
    }
    return (threshold1 + threshold2) / 2.0;
  }
 
  function CreateImageData() {
    Image.data = new Uint8ClampedArray(Image.width * Image.height * 4);
    var Converter;
    var x, y;
    for (y = 0; y < Image.height; y++) {
      for (x = 0; x < Image.width; x++) {
        Converter = y * 4 * Image.width;
        Image.data[Converter + x * 4] = Image.table[x][y][0];
        Image.data[Converter + x * 4 + 1] = Image.table[x][y][1];
        Image.data[Converter + x * 4 + 2] = Image.table[x][y][2];
        Image.data[Converter + x * 4 + 3] = Image.table[x][y][3];
      }
    }
  }
 
  function CreateScanImageData() {
    ScanImage.data = new Uint8ClampedArray(ScanImage.width * ScanImage.height * 4);
    var Converter;
    var x, y;
    for (y = 0; y < ScanImage.height; y++) {
      for (x = 0; x < ScanImage.width; x++) {
        Converter = y * 4 * ScanImage.width;
        ScanImage.data[Converter + x * 4] = ScanImage.table[x][y][0];
        ScanImage.data[Converter + x * 4 + 1] = ScanImage.table[x][y][1];
        ScanImage.data[Converter + x * 4 + 2] = ScanImage.table[x][y][2];
        ScanImage.data[Converter + x * 4 + 3] = ScanImage.table[x][y][3];
      }
    }
  }
 
  function CreateTable() {
    Image.table = [];
    var tempArray = [];
    var i, j;
    for (i = 0; i < Image.width * 4; i += 4) {
      tempArray = [];
      for (j = i; j < Image.data.length; j += Image.width * 4) {
        tempArray.push([Image.data[j], Image.data[j + 1], Image.data[j + 2], Image.data[j + 3]]);
      }
      Image.table.push(tempArray);
    }
  }
 
  function CreateScanTable() {
    ScanImage.table = [];
    var tempArray = [];
    var i, j;
    for (i = 0; i < ScanImage.width * 4; i += 4) {
      tempArray = [];
      for (j = i; j < ScanImage.data.length; j += ScanImage.width * 4) {
        tempArray.push([ScanImage.data[j], ScanImage.data[j + 1], ScanImage.data[j + 2], ScanImage.data[j + 3]]);
      }
      ScanImage.table.push(tempArray);
    }
  }
 
  function EnlargeTable(h, w) {
    var TempArray = [];
    var x, y, i;
    for (x = 0; x < Image.width; x++) {
      TempArray = [];
      for (y = 0; y < Image.height; y++) {
        for (i = 0; i < h; i++) {
          TempArray.push(Image.table[x][y]);
        }
      }
      Image.table[x] = TempArray.slice();
    }
    TempArray = Image.table.slice();
    for (x = 0; x < Image.width; x++) {
      for (i = 0; i < w; i++) {
        Image.table[x * w + i] = TempArray[x].slice();
      }
    }
    Image.width = Image.table.length;
    Image.height = Image.table[0].length;
    CreateImageData();
  }
 
  function ScaleHeight(scale) {
    var tempArray = [];
    var avrgRed = 0;
    var avrgGreen = 0;
    var avrgBlue = 0;
    var i, j, k;
    for (i = 0; i < Image.height - scale; i += scale) {
      for (j = 0; j < Image.width; j++) {
        avrgRed = 0;
        avrgGreen = 0;
        avrgBlue = 0;
        for (k = i; k < i + scale; k++) {
          avrgRed += Image.table[j][k][0];
          avrgGreen += Image.table[j][k][1];
          avrgBlue += Image.table[j][k][2];
        }
        tempArray.push(avrgRed / scale);
        tempArray.push(avrgGreen / scale);
        tempArray.push(avrgBlue / scale);
        tempArray.push(255);
      }
    }
    return new Uint8ClampedArray(tempArray);
  }
 
  function Intersects(rectOne, rectTwo) {
    return (rectOne[0][0] <= rectTwo[0][1] &&
      rectTwo[0][0] <= rectOne[0][1] &&
      rectOne[1][0] <= rectTwo[1][1] &&
      rectTwo[1][0] <= rectOne[1][1]);
  }
 
  function maxLocalization(max, maxPos, data) {
    var originalMax = max;
    var rects = [];
    var x, y, i;
    do {
      var startX = maxPos % Image.width;
      var startY = (maxPos - startX) / Image.width;
      var minY = 0;
      var maxY = Image.height;
      var minX = 0;
      var maxX = Image.width - 1;
      for (y = startY; y < Image.height - 1; y++) {
        if (Image.table[startX][y + 1][0] === 0) {
          maxY = y;
          break;
        }
      }
      for (y = startY; y > 0; y--) {
        if (Image.table[startX][y - 1][0] === 0) {
          minY = y;
          break;
        }
      }
      for (x = startX; x < Image.width - 1; x++) {
        if (Image.table[x + 1][startY][0] === 0) {
          maxX = x;
          break;
        }
      }
      for (x = startX; x > 0; x--) {
        if (Image.table[x - 1][startY][0] === 0) {
          minX = x;
          break;
        }
      }
      for (y = minY * Image.width; y <= maxY * Image.width; y += Image.width) {
        for (x = minX; x <= maxX; x++) {
          data[y + x] = 0;
        }
      }
      var newRect = [
        [minX, maxX],
        [minY, maxY]
      ];
      for (i = 0; i < rects.length; i++) {
        if (Intersects(newRect, rects[i])) {
          if (rects[i][0][1] - rects[i][0][0] > newRect[0][1] - newRect[0][0]) {
            rects[i][0][0] = rects[i][0][0] < newRect[0][0] ? rects[i][0][0] : newRect[0][0];
            rects[i][0][1] = rects[i][0][1] > newRect[0][1] ? rects[i][0][1] : newRect[0][1];
            newRect = [];
            break;
          } else {
            rects[i][0][0] = rects[i][0][0] < newRect[0][0] ? rects[i][0][0] : newRect[0][0];
            rects[i][0][1] = rects[i][0][1] > newRect[0][1] ? rects[i][0][1] : newRect[0][1];
            rects[i][1][0] = newRect[1][0];
            rects[i][1][1] = newRect[1][1];
            newRect = [];
            break;
          }
        }
      }
      if (newRect.length > 0) {
        rects.push(newRect);
      }
      max = 0;
      maxPos = 0;
      var newMaxPos = 0;
      for (i = 0; i < data.length; i++) {
        if (data[i] > max) {
          max = data[i];
          maxPos = i;
        }
      }
    } while (max > originalMax * 0.70);
    return rects;
  }
 
  function ImgProcessing() {
    greyScale(Image.data);
    var newData = IntensityGradient(Image.data, Image.width);
    newData = BoxFilter(newData, Image.width, 15);
    var min = newData[0];
    var i, x, y;
    for (i = 1; i < newData.length; i++) {
      min = min > newData[i] ? newData[i] : min;
    }
    var max = 0;
    var maxPos = 0;
    var avrgLight = 0;
    for (i = 0; i < newData.length; i++) {
      newData[i] = Math.round((newData[i] - min));
      avrgLight += newData[i];
      if (max < newData[i]) {
        max = newData[i];
        maxPos = i;
      }
    }
    avrgLight /= newData.length;
    if (avrgLight < 15) {
      newData = BoxFilter(newData, Image.width, 8);
      min = newData[0];
      for (i = 1; i < newData.length; i++) {
        min = min > newData[i] ? newData[i] : min;
      }
      max = 0;
      maxPos = 0;
      for (i = 0; i < newData.length; i++) {
        newData[i] = Math.round((newData[i] - min));
        if (max < newData[i]) {
          max = newData[i];
          maxPos = i;
        }
      }
    }
    var hist = [];
    for (i = 0; i <= max; i++) {
      hist[i] = 0;
    }
    for (i = 0; i < newData.length; i++) {
      hist[newData[i]] = hist[newData[i]] + 1;
    }
    var thresh = otsu(hist, newData.length);
    for (i = 0; i < newData.length; i++) {
      if (newData[i] < thresh) {
        Image.data[i * 4] = Image.data[i * 4 + 1] = Image.data[i * 4 + 2] = 0;
      } else {
        Image.data[i * 4] = Image.data[i * 4 + 1] = Image.data[i * 4 + 2] = 255;
      }
    }
    CreateTable();
    var rects = maxLocalization(max, maxPos, newData);
    var feedBack = [];
    for (i = 0; i < rects.length; i++) {
      feedBack.push({
        x: rects[i][0][0],
        y: rects[i][1][0],
        width: rects[i][0][1] - rects[i][0][0],
        height: rects[i][1][1] - rects[i][1][0]
      });
    }
    if (feedBack.length > 0) postMessage({
      result: feedBack,
      success: "localization"
    });
    allTables = [];
    for (i = 0; i < rects.length; i++) {
      var newTable = [];
      for (x = rects[i][0][0] * 2; x < rects[i][0][1] * 2; x++) {
        var tempArray = [];
        for (y = rects[i][1][0] * 2; y < rects[i][1][1] * 2; y++) {
          tempArray.push([ScanImage.table[x][y][0], ScanImage.table[x][y][1], ScanImage.table[x][y][2], 255]);
        }
        newTable.push(tempArray);
      }
      if (newTable.length < 1) continue;
      Image.table = newTable;
      Image.width = newTable.length;
      Image.height = newTable[0].length;
      CreateImageData();
      allTables.push({
        table: newTable,
        data: new Uint8ClampedArray(Image.data),
        width: Image.width,
        height: Image.height
      });
    }
  }
 
  function showImage(data, width, height) {
    postMessage({
      result: data,
      width: width,
      height: height,
      success: "image"
    });
  }
 
  function Main() {
    ImgProcessing();
    var allResults = [];
    var tempObj;
    var tempData;
    var hist;
    var val;
    var thresh;
    var start;
    var end;
    var z, i;
    for (z = 0; z < allTables.length; z++) {
      Image = allTables[z];
      var scaled = ScaleHeight(30);
      var variationData;
      var incrmt = 0;
      var format = "";
      var first = true;
      var eanStatistics = {};
      var eanOrder = [];
      Selection = false;
      do {
        tempData = scaled.subarray(incrmt, incrmt + Image.width * 4);
        hist = [];
        for (i = 0; i < 256; i++) {
          hist[i] = 0;
        }
        for (i = 0; i < tempData.length; i += 4) {
          val = Math.round((tempData[i] + tempData[i + 1] + tempData[i + 2]) / 3);
          hist[val] = hist[val] + 1;
        }
        thresh = otsu(hist, tempData.length / 4);
        start = thresh < 41 ? 1 : thresh - 40;
        end = thresh > 254 - 40 ? 254 : thresh + 40;
        variationData = yStraighten(tempData, start, end);
        Selection = BinaryString(variationData);
        if (Selection.string) {
          format = Selection.format;
          tempObj = Selection;
          Selection = Selection.string;
          if (format === "EAN-13") {
            if (typeof eanStatistics[Selection] === 'undefined') {
              eanStatistics[Selection] = {
                count: 1,
                correction: tempObj.correction
              };
              eanOrder.push(Selection);
            } else {
              eanStatistics[Selection].count = eanStatistics[Selection].count + 1;
              eanStatistics[Selection].correction = eanStatistics[Selection].correction + tempObj.correction;
            }
            Selection = false;
          }
        } else {
          Selection = false;
        }
        incrmt += Image.width * 4;
      } while (!Selection && incrmt < scaled.length);
      if (Selection && format !== "EAN-13") allResults.push({
        Format: format,
        Value: Selection
      });
      if (format === "EAN-13") Selection = false;
      if (!Selection) {
        EnlargeTable(4, 2);
        incrmt = 0;
        scaled = ScaleHeight(20);
        do {
          tempData = scaled.subarray(incrmt, incrmt + Image.width * 4);
          hist = [];
          for (i = 0; i < 256; i++) {
            hist[i] = 0;
          }
          for (i = 0; i < tempData.length; i += 4) {
            val = Math.round((tempData[i] + tempData[i + 1] + tempData[i + 2]) / 3);
            hist[val] = hist[val] + 1;
          }
          thresh = otsu(hist, tempData.length / 4);
          start = thresh < 40 ? 0 : thresh - 40;
          end = thresh > 255 - 40 ? 255 : thresh + 40;
          variationData = yStraighten(tempData, start, end);
          Selection = BinaryString(variationData);
          if (Selection.string) {
            format = Selection.format;
            tempObj = Selection;
            Selection = Selection.string;
            if (format === "EAN-13") {
              if (typeof eanStatistics[Selection] === 'undefined') {
                eanStatistics[Selection] = {
                  count: 1,
                  correction: tempObj.correction
                };
                eanOrder.push(Selection);
              } else {
                eanStatistics[Selection].count = eanStatistics[Selection].count + 1;
                eanStatistics[Selection].correction = eanStatistics[Selection].correction + tempObj.correction;
              }
              Selection = false;
            }
          } else {
            Selection = false;
          }
          incrmt += Image.width * 4;
        } while (!Selection && incrmt < scaled.length);
        if (format === "EAN-13") {
          var points = {};
          for (var key in eanStatistics) {
            eanStatistics[key].correction = eanStatistics[key].correction / eanStatistics[key].count;
            var pointTemp = eanStatistics[key].correction;
            pointTemp -= eanStatistics[key].count;
            pointTemp += eanOrder.indexOf(key);
            points[key] = pointTemp;
          }
          var minPoints = Number.POSITIVE_INFINITY;
          var tempString = "";
          for (var point in points) {
            if (points[point] < minPoints) {
              minPoints = points[point];
              tempString = key;
            }
          }
          if (minPoints < 11) {
            Selection = tempString;
          } else {
            Selection = false;
          }
        }
        if (Selection) allResults.push({
          Format: format,
          Value: Selection
        });
      }
      if (allResults.length > 0 && !Multiple) break;
    }
    return allResults;
  }
 
  function yStraighten(img, start, end) {
    var average = 0;
    var threshold;
    var newImg = new Uint8ClampedArray(Image.width * (end - start + 1) * 4);
    var i, j;
    for (i = 0; i < newImg.length; i++) {
      newImg[i] = 255;
    }
    for (i = 0; i < Image.width * 4; i += 4) {
      threshold = end;
      average = (img[i] + img[i + 1] + img[i + 2]) / 3;
      if (i < Image.width * 4 - 4) {
        average += (img[i + 4] + img[i + 5] + img[i + 6]) / 3;
        average /= 2;
      }
      for (j = i; j < newImg.length; j += Image.width * 4) {
        if (average < threshold) {
          newImg[j] = newImg[j + 1] = newImg[j + 2] = 0;
        }
        threshold--;
      }
    }
    return newImg;
  }
 
  function CheckEan13(values, middle) {
    if (middle) {
      if (values.length !== 5) return false;
    } else {
      if (values.length !== 3) return false;
    }
    var avrg = 0;
    var i;
    for (i = 0; i < values.length; i++) {
      avrg += values[i];
    }
    avrg /= values.length;
    for (i = 0; i < values.length; i++) {
      if (values[i] / avrg < 0.5 || values[i] / avrg > 1.5) return false;
    }
    return true;
  }
 
  function TwoOfFiveStartEnd(values, start) {
    if (values.length < 5 || values.length > 6) return false;
    var maximum = 0;
    var TwoOfFiveMax = [0, 0];
    var u;
    for (u = 0; u < values.length; u++) {
      if (values[u] > maximum) {
        maximum = values[u];
        TwoOfFiveMax[0] = u;
      }
    }
    maximum = 0;
    for (u = 0; u < values.length; u++) {
      if (u === TwoOfFiveMax[0]) continue;
      if (values[u] > maximum) {
        maximum = values[u];
        TwoOfFiveMax[1] = u;
      }
    }
    if (start) {
      return TwoOfFiveMax[0] + TwoOfFiveMax[1] === 2;
    } else {
      return TwoOfFiveMax[0] + TwoOfFiveMax[1] === 2;
    }
  }
 
  function CheckInterleaved(values, start) {
    var average = 0;
    var i;
    for (i = 0; i < values.length; i++) {
      average += values[i];
    }
    average /= 4;
    if (start) {
      if (values.length !== 4) return false;
      for (i = 0; i < values.length; i++) {
        if (values[i] / average < 0.5 || values[i] / average > 1.5) return false;
      }
      return true;
    } else {
      if (values.length !== 3) return false;
      var max = 0;
      var pos;
      for (i = 0; i < values.length; i++) {
        if (values[i] > max) {
          max = values[i];
          pos = i;
        }
      }
      if (pos !== 0) return false;
      if (values[0] / average < 1.5 || values[0] / average > 2.5) return false;
      for (i = 1; i < values.length; i++) {
        if (values[i] / average < 0.5 || values[i] / average > 1.5) return false;
      }
      return true;
    }
  }
 
  function BinaryConfiguration(binaryString, type) {
    var result = [];
    var binTemp = [];
    var count = 0;
    var bars;
    var len;
    var totalBars;
    var i;
    if (type === "Code128" || type === "Code93") {
      totalBars = 6;
      len = binaryString[0];
      if (type === "Code128") len /= 2;
      for (i = 0; i < binaryString.length; i++) {
        if (binaryString[i] > len * 6) {
          binaryString.splice(i, binaryString.length);
          break;
        }
      }
      do {
        if (binaryString.length === 7 && type === "Code128") {
          result.push(binaryString.splice(0, binaryString.length));
        } else {
          result.push(binaryString.splice(0, totalBars));
        }
        if (type === "Code93" && binaryString.length < 6) binaryString.splice(0, totalBars);
      } while (binaryString.length > 0);
    }
    if (type === "Code39") {
      totalBars = 9;
      len = binaryString[0];
      for (i = 0; i < binaryString.length; i++) {
        if (binaryString[i] > len * 5) {
          binaryString.splice(i, binaryString.length);
          break;
        }
      }
      do {
        result.push(binaryString.splice(0, totalBars));
        binaryString.splice(0, 1);
      } while (binaryString.length > 0);
    }
    if (type === "EAN-13") {
      totalBars = 4;
      len = binaryString[0];
      var secureCount = 0;
      for (i = 0; i < binaryString.length; i++) {
        if (binaryString[i] > len * 6) {
          binaryString.splice(i, binaryString.length);
          break;
        }
      }
      if (CheckEan13(binaryString.splice(0, 3), false)) secureCount++;
      count = 0;
      do {
        result.push(binaryString.splice(0, totalBars));
        count++;
        if (count === 6)
          if (CheckEan13(binaryString.splice(0, 5), true)) secureCount++;
      } while (result.length < 12 && binaryString.length > 0);
      if (CheckEan13(binaryString.splice(0, 3), false)) secureCount++;
      if (secureCount < 2) return [];
    }
    if (type === "2Of5") {
      totalBars = 5;
      len = binaryString[0] / 2;
      for (i = 0; i < binaryString.length; i++) {
        if (binaryString[i] > len * 5) {
          binaryString.splice(i, binaryString.length);
          break;
        }
      }
      var temp = binaryString.splice(0, 6);
      result.push(temp);
      do {
        binTemp = [];
        for (i = 0; i < totalBars; i++) {
          binTemp.push(binaryString.splice(0, 1)[0]);
          // binaryString.splice(0, 1)[0];
        }
        result.push(binTemp);
        if (binaryString.length === 5) result.push(binaryString.splice(0, 5));
      } while (binaryString.length > 0);
    }
    if (type === "Inter2Of5") {
      totalBars = 5;
      len = binaryString[0];
      for (i = 0; i < binaryString.length; i++) {
        if (binaryString[i] > len * 5) {
          binaryString.splice(i, binaryString.length);
          break;
        }
      }
      result.push(binaryString.splice(0, 4));
      var binTempWhite = [];
      do {
        binTemp = [];
        binTempWhite = [];
        for (i = 0; i < totalBars; i++) {
          binTemp.push(binaryString.splice(0, 1)[0]);
          binTempWhite.push(binaryString.splice(0, 1)[0]);
        }
        result.push(binTemp);
        result.push(binTempWhite);
        if (binaryString.length === 3) result.push(binaryString.splice(0, 3));
      } while (binaryString.length > 0);
    }
    if (type === "Codabar") {
      totalBars = 7;
      len = binaryString[0];
      for (i = 0; i < binaryString.length; i++) {
        if (binaryString[i] > len * 5) {
          binaryString.splice(i, binaryString.length);
          break;
        }
      }
      do {
        result.push(binaryString.splice(0, totalBars));
        binaryString.splice(0, 1);
      } while (binaryString.length > 0);
    }
    return result;
  }
 
  function BinaryString(img, type) {
    var binaryString = [];
    var binTemp = [];
    var container = 255;
    var count = 0;
    var format;
    var tempString;
    var j, i;
    for (j = 0; j < img.length - Image.width * 4; j += Image.width * 4) {
      var SlicedArray = img.subarray(j, j + Image.width * 4);
      binaryString = [];
      i = 0;
      while (SlicedArray[i] === 255) {
        i += 4;
      }
      while (i < SlicedArray.length) {
        count = 0;
        container = SlicedArray[i];
        while (SlicedArray[i] === container && i < SlicedArray.length) {
          count++;
          i += 4;
        }
        binaryString.push(count);
      }
      if (binaryString.length > 2 && binaryString[0] <= binaryString[1] / 10) {
        binaryString.splice(0, 2);
      }
      var binaryHolder = binaryString.slice();
      var success = false;
      for (i = 0; i < FormatPriority.length; i++) {
        binaryString = binaryHolder.slice();
        var first;
        var second;
        binaryString = BinaryConfiguration(binaryString, FormatPriority[i]);
        if (FormatPriority[i] === "2Of5" || FormatPriority[i] === "Inter2Of5") {
          first = binaryString.splice(0, 1)[0];
          second = binaryString.splice(binaryString.length - 1, 1)[0];
        }
        binTemp = Distribution(binaryString, FormatPriority[i]);
        if (FormatPriority[i] === "EAN-13") {
          binaryString = binTemp.data;
          corrections = binTemp.correction;
        } else {
          binaryString = binTemp;
        }
        if (typeof binaryString === 'undefined') continue;
        if (binaryString.length > 4 || (FormatPriority[i] === "Code39" && binaryString.length > 2)) {
          if (FormatPriority[i] === "Code128") {
            if (CheckCode128(binaryString)) {
              binaryString = DecodeCode128(binaryString);
              success = true;
            }
          } else if (FormatPriority[i] === "Code93") {
            if (CheckCode93(binaryString)) {
              binaryString = DecodeCode93(binaryString);
              success = true;
            }
          } else if (FormatPriority[i] === "Code39") {
            if (CheckCode39(binaryString)) {
              binaryString = DecodeCode39(binaryString);
              success = true;
            }
          } else if (FormatPriority[i] === "EAN-13") {
            tempString = DecodeEAN13(binaryString);
            if (tempString) {
              if (tempString.length === 13) {
                binaryString = tempString;
                success = true;
              }
            }
          } else if (FormatPriority[i] === "2Of5" || FormatPriority[i] === "Inter2Of5") {
            if (FormatPriority[i] === "2Of5") {
              if (typeof first !== 'undefined')
                if (!TwoOfFiveStartEnd(first, true)) continue;
              if (typeof second !== 'undefined')
                if (!TwoOfFiveStartEnd(second, false)) continue;
            }
            if (FormatPriority[i] === "Inter2Of5") {
              if (typeof first !== 'undefined')
                if (!CheckInterleaved(first, true)) continue;
              if (typeof second !== 'undefined')
                if (!CheckInterleaved(second, false)) continue;
            }
            tempString = Decode2Of5(binaryString);
            if (tempString) {
              binaryString = tempString;
              success = true;
            }
          } else if (FormatPriority[i] === "Codabar") {
            tempString = DecodeCodaBar(binaryString);
            if (tempString) {
              binaryString = tempString;
              success = true;
            }
          }
        }
        if (success) {
          format = FormatPriority[i];
          if (format === "Inter2Of5") format = "Interleaved 2 of 5";
          if (format === "2Of5") format = "Standard 2 of 5";
          break;
        }
      }
      if (success) break;
    }
    if (format === "Code128") {
      if (typeof binaryString.string === 'string') {
        return binaryString;
      } else {
        return {
          string: false
        };
      }
    }
    if (typeof binaryString === 'string') {
      if (format === "EAN-13") {
        return {
          string: binaryString,
          format: format,
          correction: corrections
        };
      } else {
        return {
          string: binaryString,
          format: format
        };
      }
    } else {
      return {
        string: false
      };
    }
  }
 
  function Distribution(totalBinArray, type) {
    var testData = 0;
    var result = [];
    var totalBars;
    var total;
    var maxLength;
    var k, i, j;
    var blackMax;
    var whiteMax;
    var wideAvrg;
    var narrowAvrg;
    var prevPos;
    var wideValues;
    var max;
 
    type = availableFormats.indexOf(type);
 
    if (type === 0) {
      total = 11;
      totalBars = 6;
      maxLength = 4;
    } else if (type === 1) {
      total = 9;
      totalBars = 6;
      maxLength = 4;
    } else if (type === 2) {
      total = 12;
      totalBars = 9;
    } else if (type === 3) {
      total = 7;
      totalBars = 4;
      maxLength = 4;
    } else if (type === 6) {
      totalBars = 7;
    }
    for (k = 0; k < totalBinArray.length; k++) {
      var BinArray = totalBinArray[k];
      var sum = 0;
      var counter = 0;
      var tempBin = [];
      var narrowArr = [];
      var wideArr = [];
      if (type === 6) {
        var upperTolerance = 1.5;
        var lowerTolerance = 1 / 2;
        if (BinArray.length !== 7) return [];
        if (k === 0 || k === totalBinArray.length - 1) {
          whiteMax = [
            [0, 0],
            [0, 0]
          ];
          blackMax = [0, 0];
          for (i = 0; i < BinArray.length; i++) {
            if (i % 2 === 0) {
              if (BinArray[i] > blackMax[0]) {
                blackMax[0] = BinArray[i];
                blackMax[1] = i;
              }
            } else {
              if (BinArray[i] > whiteMax[0][0]) {
                whiteMax[0][0] = BinArray[i];
                prevPos = whiteMax[0][1];
                whiteMax[0][1] = i;
                i = prevPos - 1;
                continue;
              }
              if (BinArray[i] > whiteMax[1][0] && i !== whiteMax[0][1]) {
                whiteMax[1][0] = BinArray[i];
                whiteMax[1][1] = i;
              }
            }
          }
          if (SecureCodabar) {
            wideAvrg = whiteMax[0][0] + whiteMax[1][0] + blackMax[0];
            wideAvrg /= 3;
            wideValues = [whiteMax[0][0], whiteMax[1][0], blackMax[0]];
            for (i = 0; i < wideValues.length; i++) {
              if (wideValues[i] / wideAvrg > upperTolerance || wideValues[i] / wideAvrg < lowerTolerance) return [];
            }
            narrowAvrg = 0;
            for (i = 0; i < BinArray.length; i++) {
              if (i === blackMax[1] || i === whiteMax[0][1] || i === whiteMax[1][1]) continue;
              narrowAvrg += BinArray[i];
            }
            narrowAvrg /= 4;
            for (i = 0; i < BinArray.length; i++) {
              if (i === blackMax[1] || i === whiteMax[0][1] || i === whiteMax[1][1]) continue;
              if (BinArray[i] / narrowAvrg > upperTolerance || BinArray[i] / narrowAvrg < lowerTolerance) return [];
            }
          }
          for (i = 0; i < BinArray.length; i++) {
            if (i === blackMax[1] || i === whiteMax[0][1] || i === whiteMax[1][1]) {
              tempBin.push(1);
            } else {
              tempBin.push(0);
            }
          }
        } else {
          blackMax = [0, 0];
          whiteMax = [0, 0];
          for (i = 0; i < BinArray.length; i++) {
            if (i % 2 === 0) {
              if (BinArray[i] > blackMax[0]) {
                blackMax[0] = BinArray[i];
                blackMax[1] = i;
              }
            } else {
              if (BinArray[i] > whiteMax[0]) {
                whiteMax[0] = BinArray[i];
                whiteMax[1] = i;
              }
            }
          }
          if (blackMax[0] / whiteMax[0] > 1.55) {
            var tempArray = blackMax;
            blackMax = [tempArray, [0, 0],
              [0, 0]
            ];
            for (i = 0; i < BinArray.length; i++) {
              if (i % 2 === 0) {
                if (BinArray[i] > blackMax[1][0] && i !== blackMax[0][1]) {
                  blackMax[1][0] = BinArray[i];
                  prevPos = blackMax[1][1];
                  blackMax[1][1] = i;
                  i = prevPos - 1;
                  continue;
                }
                if (BinArray[i] > blackMax[2][0] && i !== blackMax[0][1] && i !== blackMax[1][1]) {
                  blackMax[2][0] = BinArray[i];
                  blackMax[2][1] = i;
                }
              }
            }
            if (SecureCodabar) {
              wideAvrg = blackMax[0][0] + blackMax[1][0] + blackMax[2][0];
              wideAvrg /= 3;
              for (i = 0; i < blackMax.length; i++) {
                if (blackMax[i][0] / wideAvrg > upperTolerance || blackMax[i][0] / wideAvrg < lowerTolerance) return [];
              }
              narrowAvrg = 0;
              for (i = 0; i < BinArray.length; i++) {
                if (i === blackMax[0][1] || i === blackMax[1][1] || i === blackMax[2][1]) continue;
                narrowAvrg += BinArray[i];
              }
              narrowAvrg /= 4;
              for (i = 0; i < BinArray.length; i++) {
                if (i === blackMax[0][1] || i === blackMax[1][1] || i === blackMax[2][1]) continue;
                if (BinArray[i] / narrowAvrg > upperTolerance || BinArray[i] / narrowAvrg < lowerTolerance) return [];
              }
            }
            for (i = 0; i < BinArray.length; i++) {
              if (i === blackMax[0][1] || i === blackMax[1][1] || i === blackMax[2][1]) {
                tempBin.push(1);
              } else {
                tempBin.push(0);
              }
            }
          } else {
            if (SecureCodabar) {
              wideAvrg = blackMax[0] + whiteMax[0];
              wideAvrg /= 2;
              if (blackMax[0] / wideAvrg > 1.5 || blackMax[0] / wideAvrg < 0.5) return [];
              if (whiteMax[0] / wideAvrg > 1.5 || whiteMax[0] / wideAvrg < 0.5) return [];
              narrowAvrg = 0;
              for (i = 0; i < BinArray.length; i++) {
                if (i === blackMax[1] || i === whiteMax[1]) continue;
                narrowAvrg += BinArray[i];
              }
              narrowAvrg /= 5;
              for (i = 0; i < BinArray.length; i++) {
                if (i === blackMax[1] || i === whiteMax[1]) continue;
                if (BinArray[i] / narrowAvrg > upperTolerance || BinArray[i] / narrowAvrg < lowerTolerance) return [];
              }
            }
            for (i = 0; i < BinArray.length; i++) {
              if (i === blackMax[1] || i === whiteMax[1]) {
                tempBin.push(1);
              } else {
                tempBin.push(0);
              }
            }
          }
        }
        result.push(tempBin);
        continue;
      }
      if (type === 4 || type === 5) {
        max = [
          [0, 0],
          [0, 0]
        ];
        for (i = 0; i < BinArray.length; i++) {
          if (!isFinite(BinArray[i])) return [];
          if (BinArray[i] > max[0][0]) {
            max[0][0] = BinArray[i];
            prevPos = max[0][1];
            max[0][1] = i;
            i = prevPos - 1;
          }
          if (BinArray[i] > max[1][0] && i !== max[0][1]) {
            max[1][0] = BinArray[i];
            max[1][1] = i;
          }
        }
        if (Secure2Of5) {
          wideAvrg = max[0][0] + max[1][0];
          wideAvrg /= 2;
          if (max[0][0] / wideAvrg > 1.3 || max[0][0] / wideAvrg < 0.7) return [];
          if (max[1][0] / wideAvrg > 1.3 || max[1][0] / wideAvrg < 0.7) return [];
          narrowAvrg = 0;
          for (i = 0; i < BinArray.length; i++) {
            if (i === max[0][1] || i === max[1][1]) continue;
            narrowAvrg += BinArray[i];
          }
          narrowAvrg /= 3;
          for (i = 0; i < BinArray.length; i++) {
            if (i === max[0][1] || i === max[1][1]) continue;
            if (BinArray[i] / narrowAvrg > 1.3 || BinArray[i] / narrowAvrg < 0.7) return [];
          }
        }
        for (i = 0; i < BinArray.length; i++) {
          if (i === max[0][1] || i === max[1][1]) {
            tempBin.push(1);
            continue;
          }
          tempBin.push(0);
        }
        result.push(tempBin);
        continue;
      }
      while (counter < totalBars) {
        sum += BinArray[counter];
        counter++;
      }
      if (type === 2) {
        var indexCount = [];
        blackMax = [
          [0, 0],
          [0, 0]
        ];
        whiteMax = [0, 0];
        for (j = 0; j < BinArray.length; j++) {
          if (j % 2 === 0) {
            if (BinArray[j] > blackMax[0][0]) {
              blackMax[0][0] = BinArray[j];
              prevPos = blackMax[0][1];
              blackMax[0][1] = j;
              j = prevPos;
            }
            if (BinArray[j] > blackMax[1][0] && j !== blackMax[0][1]) {
              blackMax[1][0] = BinArray[j];
              blackMax[1][1] = j;
            }
          } else {
            if (BinArray[j] > whiteMax[0]) {
              whiteMax[0] = BinArray[j];
              whiteMax[1] = j;
            }
          }
        }
        if (whiteMax[0] / blackMax[0][0] > 1.5 && whiteMax[0] / blackMax[1][0] > 1.5) {
          blackMax = [
            [0, 0],
            [0, 0]
          ];
          for (j = 0; j < BinArray.length; j++) {
            if (j % 2 !== 0) {
              if (BinArray[j] > blackMax[0][0] && j !== whiteMax[1]) {
                blackMax[0][0] = BinArray[j];
                prevPos = blackMax[0][1];
                blackMax[0][1] = j;
                j = prevPos;
              }
              if (BinArray[j] > blackMax[1][0] && j !== blackMax[0][1] && j !== whiteMax[1]) {
                blackMax[1][0] = BinArray[j];
                blackMax[1][1] = j;
              }
            }
          }
        }
        wideAvrg = blackMax[0][0] + blackMax[1][0] + whiteMax[0];
        wideAvrg /= 3;
        if (blackMax[0][0] / wideAvrg > 1.6 || blackMax[0][0] / wideAvrg < 0.4) return [];
        if (blackMax[1][0] / wideAvrg > 1.6 || blackMax[1][0] / wideAvrg < 0.4) return [];
        if (whiteMax[0] / wideAvrg > 1.6 || whiteMax[0] / wideAvrg < 0.4) return [];
        narrowAvrg = 0;
        for (i = 0; i < BinArray.length; i++) {
          if (i === blackMax[0][1] || i === blackMax[1][1] || i === whiteMax[1]) continue;
          narrowAvrg += BinArray[i];
        }
        narrowAvrg /= 6;
        for (i = 0; i < BinArray.length; i++) {
          if (i === blackMax[0][1] || i === blackMax[1][1] || i === whiteMax[1]) continue;
          if (BinArray[i] / narrowAvrg > 1.6 || BinArray[i] / narrowAvrg < 0.4) return [];
        }
        for (j = 0; j < BinArray.length; j++) {
          if (j === blackMax[0][1] || j === blackMax[1][1] || j === whiteMax[1]) {
            tempBin.push(2);
          } else {
            tempBin.push(1);
          }
        }
        result.push(tempBin);
        continue;
      }
      if (type === 3) {
        max = [
          [0, 0],
          [0, 0],
          [0, 0]
        ];
        for (j = 0; j < BinArray.length; j++) {
          if (BinArray[j] > max[0][0]) {
            max[0][0] = BinArray[j];
            prevPos = max[0][1];
            max[0][1] = j;
            j = prevPos;
          }
          if (BinArray[j] > max[1][0] && j !== max[0][1]) {
            max[1][0] = BinArray[j];
            prevPos = max[1][1];
            max[1][1] = j;
            j = prevPos;
          }
          if (BinArray[j] > max[2][0] && j !== max[0][1] && j !== max[1][1]) {
            max[2][0] = BinArray[j];
            max[2][1] = j;
          }
        }
        if (max[0][0] / max[1][0] >= 3) {
          narrowAvrg = 0;
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1]) continue;
            narrowAvrg += BinArray[j];
          }
          narrowAvrg /= 3;
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1]) continue;
            if (BinArray[j] / narrowAvrg < 0.02 || BinArray[j] / narrowAvrg > 3) return {
              data: [],
              correction: 0
            };
          }
          if (max[0][0] / narrowAvrg < 2.2 || max[0][0] / narrowAvrg > 6) return {
            data: [],
            correction: 0
          };
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1]) {
              tempBin.push(4);
            } else {
              tempBin.push(1);
            }
          }
          result.push(tempBin);
        } else if (max[0][0] / max[2][0] > 2) {
          wideAvrg = max[0][0] + max[1][0];
          wideAvrg /= 5;
          if (max[0][0] / (wideAvrg * 3) < 0.02 || max[0][0] / (wideAvrg * 3) > 3) return {
            data: [],
            correction: 0
          };
          if (max[1][0] / (wideAvrg * 2) < 0.02 || max[1][0] / (wideAvrg * 2) > 3) return {
            data: [],
            correction: 0
          };
          narrowAvrg = 0;
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1] || j === max[1][1]) continue;
            narrowAvrg += BinArray[j];
          }
          narrowAvrg /= 2;
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1] || j === max[1][1]) continue;
            if (BinArray[j] / narrowAvrg < 0.02 || BinArray[j] / narrowAvrg > 3) return {
              data: [],
              correction: 0
            };
          }
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1]) {
              tempBin.push(3);
            } else if (j === max[1][1]) {
              tempBin.push(2);
            } else {
              tempBin.push(1);
            }
          }
          result.push(tempBin);
        } else {
          if (max[0][1] % 2 === max[1][1] % 2 && max[0][1] % 2 === max[2][1] % 2) {
            var modMem = max[0][1] % 2;
            max[2] = [0, 0];
            for (j = 0; j < BinArray.length; j++) {
              if (j % 2 === modMem) continue;
              if (BinArray[j] > max[2][0]) {
                max[2][0] = BinArray[j];
                max[2][1] = j;
              }
            }
          }
          wideAvrg = max[0][0] + max[1][0] + max[2][0];
          wideAvrg /= 3;
          for (j = 0; j < max.length; j++) {
            if (max[j][0] / wideAvrg < 0.02 || max[j][0] / wideAvrg > 3) return {
              data: [],
              correction: 0
            };
          }
          var narrow = 0;
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1] || j === max[1][1] || j === max[2][1]) continue;
            narrow = BinArray[j];
          }
          if (wideAvrg / narrow < 0.02 || wideAvrg / narrow > 3) return {
            data: [],
            correction: 0
          };
          for (j = 0; j < BinArray.length; j++) {
            if (j === max[0][1] || j === max[1][1] || j === max[2][1]) {
              tempBin.push(2);
            } else {
              tempBin.push(1);
            }
          }
          result.push(tempBin);
        }
        for (j = 0; j < tempBin.length; j++) {
          testData += Math.abs(tempBin[j] - (BinArray[j] / sum) * total);
        }
        continue;
      }
      counter = 0;
      while (counter < totalBars) {
        tempBin.push((BinArray[counter] / sum) * total);
        counter++;
      }
      counter = 0;
      while (counter < totalBars) {
        tempBin[counter] = tempBin[counter] > maxLength ? maxLength : tempBin[counter];
        tempBin[counter] = tempBin[counter] < 1 ? 1 : tempBin[counter];
        tempBin[counter] = Math.round(tempBin[counter]);
        counter++;
      }
      if (type === 3) {
        var checking = 0;
        for (i = 0; i < tempBin.length; i++) {
          checking += tempBin[i];
        }
        if (checking > 7) {
          max = 0;
          var hitIndex = 0;
          for (i = 0; i < tempBin.length; i++) {
            if (tempBin[i] > max) {
              max = tempBin[i];
              hitIndex = i;
            }
          }
          tempBin[hitIndex] = max - (checking - 7);
        }
      }
      if (type === 3) {
        for (i = 0; i < tempBin.length; i++) {
          testData += Math.abs(tempBin[i] - (BinArray[i] / sum) * total);
        }
      }
      result.push(tempBin);
    }
    if (type === 3) {
      return {
        data: result,
        correction: testData
      };
    } else {
      return result;
    }
  }
 
  function CheckCode128(string) {
    var checksum = string[string.length - 2].join("");
    var i;
    checksum = Code128Encoding.value.indexOf(checksum);
    if (checksum === -1) return false;
    var summarizer = Code128Encoding.value.indexOf(string[0].join(""));
    if (summarizer === -1) return false;
    var startChar = Code128Encoding[string[0].join("")];
    if (typeof startChar === 'undefined') return false;
    if (startChar !== "A" && startChar !== "B" && startChar !== "C") return false;
    for (i = 1; i < (string.length - 2); i++) {
      summarizer += Code128Encoding.value.indexOf(string[i].join("")) * i;
      if (Code128Encoding.value.indexOf(string[i].join("")) === -1) return false;
    }
    return (summarizer % 103 === checksum);
  }
 
  function Decode2Of5(string) {
    var result = "";
    var i;
    for (i = 0; i < string.length; i++) {
      if (TwoOfFiveEncoding.indexOf(string[i].join("")) === -1) return false;
      result += TwoOfFiveEncoding.indexOf(string[i].join(""));
    }
    return result;
  }
 
  function DecodeCodaBar(string) {
    var result = "";
    var start = string[0].join("");
    var end = string[string.length - 1].join("");
    var i;
    if (!(CodaBarEncoding[start] === "A" || CodaBarEncoding[start] === "B" || CodaBarEncoding[start] === "C" || CodaBarEncoding[start] === "D")) return false;
    if (!(CodaBarEncoding[end] === "A" || CodaBarEncoding[end] === "B" || CodaBarEncoding[end] === "C" || CodaBarEncoding[end] === "D")) return false;
    for (i = 1; i < string.length - 1; i++) {
      if (typeof CodaBarEncoding[string[i].join("")] === 'undefined') return false;
      result += CodaBarEncoding[string[i].join("")];
    }
    return result;
  }
 
  function DecodeEAN13(string) {
    if (string.length !== 12) return false;
    var leftSide = string.slice(0, 6);
    var trigger = false;
    var rightSide = string.slice(6, string.length);
    var i;
    for (i = 0; i < leftSide.length; i++) {
      leftSide[i] = leftSide[i].join("");
      if (leftSide[i].length !== 4) {
        trigger = true;
        break;
      }
    }
    if (trigger) return false;
    for (i = 0; i < rightSide.length; i++) {
      rightSide[i] = rightSide[i].join("");
      if (rightSide[i].length !== 4) {
        trigger = true;
        break;
      }
    }
    if (trigger) return false;
    var decodeFormat = [];
    for (i = 0; i < leftSide.length; i++) {
      if (typeof EAN13Encoding.L[leftSide[i]] !== 'undefined') {
        decodeFormat.push("L");
      } else if (typeof EAN13Encoding.G[leftSide[i]] !== 'undefined') {
        decodeFormat.push("G");
      } else {
        trigger = true;
        break;
      }
    }
    if (trigger) return false;
    var resultArray = [];
    if (typeof EAN13Encoding.formats[decodeFormat.join("")] === 'undefined') return false;
    resultArray.push(EAN13Encoding.formats[decodeFormat.join("")]);
    for (i = 0; i < leftSide.length; i++) {
      if (typeof EAN13Encoding[decodeFormat[i]][leftSide[i]] === 'undefined') {
        trigger = true;
        break;
      }
      resultArray.push(EAN13Encoding[decodeFormat[i]][leftSide[i]]);
    }
    if (trigger) return false;
    for (i = 0; i < rightSide.length; i++) {
      if (typeof EAN13Encoding.R[rightSide[i]] === 'undefined') {
        trigger = true;
        break;
      }
      resultArray.push(EAN13Encoding.R[rightSide[i]]);
    }
    if (trigger) return false;
    var weight = 3;
    var sum = 0;
    for (i = resultArray.length - 2; i >= 0; i--) {
      sum += resultArray[i] * weight;
      if (weight === 3) {
        weight = 1;
      } else {
        weight = 3;
      }
    }
    sum = (10 - sum % 10) % 10;
    if (resultArray[resultArray.length - 1] === sum) {
      return resultArray.join("");
    } else {
      return false;
    }
  }
 
  function CheckCode93(string) {
    var checkOne = string[string.length - 3].join("");
    var checkTwo = string[string.length - 2].join("");
    var failSafe = true;
    if (typeof Code93Encoding[checkOne] === 'undefined') return false;
    if (typeof Code93Encoding[checkTwo] === 'undefined') return false;
    var checkSum = Code93Encoding[checkOne].value;
    var weight = 1;
    var sum = 0;
    var i;
    for (i = string.length - 4; i > 0; i--) {
      failSafe = typeof Code93Encoding[string[i].join("")] === 'undefined' ? false : failSafe;
      if (!failSafe) break;
      sum += Code93Encoding[string[i].join("")].value * weight;
      weight++;
      if (weight > 20) weight = 1;
    }
    var firstCheck = sum % 47;
    var firstBool = firstCheck === checkSum;
    if (!firstBool) return false;
    if (!failSafe) return false;
    sum = firstCheck;
    weight = 2;
    checkSum = Code93Encoding[checkTwo].value;
    for (i = string.length - 4; i > 0; i--) {
      failSafe = typeof Code93Encoding[string[i].join("")] === 'undefined' ? false : failSafe;
      if (!failSafe) break;
      sum += Code93Encoding[string[i].join("")].value * weight;
      weight++;
      if (weight > 15) weight = 1;
    }
    var secondCheck = sum % 47;
    var secondBool = secondCheck === checkSum;
    return secondBool && firstBool;
  }
 
  function CheckCode39(string) {
    var trigger = true;
    if (typeof Code39Encoding[string[0].join("")] === 'undefined') return false;
    if (Code39Encoding[string[0].join("")].character !== "*") return false;
    if (typeof Code39Encoding[string[string.length - 1].join("")] === 'undefined') return false;
    if (Code39Encoding[string[string.length - 1].join("")].character !== "*") return false;
    for (i = 1; i < string.length - 1; i++) {
      if (typeof Code39Encoding[string[i].join("")] === 'undefined') {
        trigger = false;
        break;
      }
    }
    return trigger;
  }
 
  function DecodeCode39(string) {
    var resultString = "";
    var special = false;
    var character = "";
    var specialchar = "";
    for (i = 1; i < string.length - 1; i++) {
      character = Code39Encoding[string[i].join("")].character;
      if (character === "$" || character === "/" || character === "+" || character === "%") {
        // if next character exists => this a special character
        if (i + 1 < string.length - 1) {
          special = true;
          specialchar = character;
          continue;
        }
      }
      if (special) {
        if (typeof ExtendedEncoding[specialchar + character] === 'undefined') {} else {
          resultString += ExtendedEncoding[specialchar + character];
        }
        special = false;
        continue;
      }
      resultString += character;
    }
    return resultString;
  }
 
  function DecodeCode93(string) {
    var resultString = "";
    var special = false;
    var character = "";
    var specialchar = "";
    for (i = 1; i < string.length - 3; i++) {
      character = Code93Encoding[string[i].join("")].character;
      if (character === "($)" || character === "(/)" || character === "(+)" || character === "(%)") {
        special = true;
        specialchar = character[1];
        continue;
      }
      if (special) {
        if (typeof ExtendedEncoding[specialchar + character] === 'undefined') {} else {
          resultString += ExtendedEncoding[specialchar + character];
        }
        special = false;
        continue;
      }
      resultString += character;
    }
    return resultString;
  }
 
  function DecodeCode128(string) {
    var set = Code128Encoding[string[0].join("")];
    var symbol;
    var Code128Format = "Code128";
    var resultString = "";
    var i;
    for (i = 1; i < (string.length - 2); i++) {
      symbol = Code128Encoding[string[i].join("")][set];
      switch (symbol) {
        case "FNC1":
          if (i === 1) Code128Format = "GS1-128";
          break;
        case "FNC2":
        case "FNC3":
        case "FNC4":
          break;
        case "SHIFT_B":
          i++;
          resultString += Code128Encoding[string[i].join("")].B;
          break;
        case "SHIFT_A":
          i++;
          resultString += Code128Encoding[string[i].join("")].A;
          break;
        case "Code_A":
          set = "A";
          break;
        case "Code_B":
          set = "B";
          break;
        case "Code_C":
          set = "C";
          break;
        default:
          resultString += symbol;
      }
    }
    return {
      string: resultString,
      format: Code128Format
    };
  }
  TwoOfFiveEncoding = ["00110", "10001", "01001", "11000", "00101", "10100", "01100", "00011", "10010", "01010"];
  Code128Encoding = {
    "212222": {
      A: " ",
      B: " ",
      C: "00"
    },
    "222122": {
      A: "!",
      B: "!",
      C: "01"
    },
    "222221": {
      A: '"',
      B: '"',
      C: "02"
    },
    "121223": {
      A: "#",
      B: "#",
      C: "03"
    },
    "121322": {
      A: "$",
      B: "$",
      C: "04"
    },
    "131222": {
      A: "%",
      B: "%",
      C: "05"
    },
    "122213": {
      A: "&",
      B: "&",
      C: "06"
    },
    "122312": {
      A: "'",
      B: "'",
      C: "07"
    },
    "132212": {
      A: "(",
      B: "(",
      C: "08"
    },
    "221213": {
      A: ")",
      B: ")",
      C: "09"
    },
    "221312": {
      A: "*",
      B: "*",
      C: "10"
    },
    "231212": {
      A: "+",
      B: "+",
      C: "11"
    },
    "112232": {
      A: ",",
      B: ",",
      C: "12"
    },
    "122132": {
      A: "-",
      B: "-",
      C: "13"
    },
    "122231": {
      A: ".",
      B: ".",
      C: "14"
    },
    "113222": {
      A: "/",
      B: "/",
      C: "15"
    },
    "123122": {
      A: "0",
      B: "0",
      C: "16"
    },
    "123221": {
      A: "1",
      B: "1",
      C: "17"
    },
    "223211": {
      A: "2",
      B: "2",
      C: "18"
    },
    "221132": {
      A: "3",
      B: "3",
      C: "19"
    },
    "221231": {
      A: "4",
      B: "4",
      C: "20"
    },
    "213212": {
      A: "5",
      B: "5",
      C: "21"
    },
    "223112": {
      A: "6",
      B: "6",
      C: "22"
    },
    "312131": {
      A: "7",
      B: "7",
      C: "23"
    },
    "311222": {
      A: "8",
      B: "8",
      C: "24"
    },
    "321122": {
      A: "9",
      B: "9",
      C: "25"
    },
    "321221": {
      A: ":",
      B: ":",
      C: "26"
    },
    "312212": {
      A: ";",
      B: ";",
      C: "27"
    },
    "322112": {
      A: "<",
      B: "<",
      C: "28"
    },
    "322211": {
      A: "=",
      B: "=",
      C: "29"
    },
    "212123": {
      A: ">",
      B: ">",
      C: "30"
    },
    "212321": {
      A: "?",
      B: "?",
      C: "31"
    },
    "232121": {
      A: "@",
      B: "@",
      C: "32"
    },
    "111323": {
      A: "A",
      B: "A",
      C: "33"
    },
    "131123": {
      A: "B",
      B: "B",
      C: "34"
    },
    "131321": {
      A: "C",
      B: "C",
      C: "35"
    },
    "112313": {
      A: "D",
      B: "D",
      C: "36"
    },
    "132113": {
      A: "E",
      B: "E",
      C: "37"
    },
    "132311": {
      A: "F",
      B: "F",
      C: "38"
    },
    "211313": {
      A: "G",
      B: "G",
      C: "39"
    },
    "231113": {
      A: "H",
      B: "H",
      C: "40"
    },
    "231311": {
      A: "I",
      B: "I",
      C: "41"
    },
    "112133": {
      A: "J",
      B: "J",
      C: "42"
    },
    "112331": {
      A: "K",
      B: "K",
      C: "43"
    },
    "132131": {
      A: "L",
      B: "L",
      C: "44"
    },
    "113123": {
      A: "M",
      B: "M",
      C: "45"
    },
    "113321": {
      A: "N",
      B: "N",
      C: "46"
    },
    "133121": {
      A: "O",
      B: "O",
      C: "47"
    },
    "313121": {
      A: "P",
      B: "P",
      C: "48"
    },
    "211331": {
      A: "Q",
      B: "Q",
      C: "49"
    },
    "231131": {
      A: "R",
      B: "R",
      C: "50"
    },
    "213113": {
      A: "S",
      B: "S",
      C: "51"
    },
    "213311": {
      A: "T",
      B: "T",
      C: "52"
    },
    "213131": {
      A: "U",
      B: "U",
      C: "53"
    },
    "311123": {
      A: "V",
      B: "V",
      C: "54"
    },
    "311321": {
      A: "W",
      B: "W",
      C: "55"
    },
    "331121": {
      A: "X",
      B: "X",
      C: "56"
    },
    "312113": {
      A: "Y",
      B: "Y",
      C: "57"
    },
    "312311": {
      A: "Z",
      B: "Z",
      C: "58"
    },
    "332111": {
      A: "[",
      B: "[",
      C: "59"
    },
    "314111": {
      A: "\\",
      B: "\\",
      C: "60"
    },
    "221411": {
      A: "]",
      B: "]",
      C: "61"
    },
    "431111": {
      A: "^",
      B: "^",
      C: "62"
    },
    "111224": {
      A: "_",
      B: "_",
      C: "63"
    },
    "111422": {
      A: "NUL",
      B: "`",
      C: "64"
    },
    "121124": {
      A: "SOH",
      B: "a",
      C: "65"
    },
    "121421": {
      A: "STX",
      B: "b",
      C: "66"
    },
    "141122": {
      A: "ETX",
      B: "c",
      C: "67"
    },
    "141221": {
      A: "EOT",
      B: "d",
      C: "68"
    },
    "112214": {
      A: "ENQ",
      B: "e",
      C: "69"
    },
    "112412": {
      A: "ACK",
      B: "f",
      C: "70"
    },
    "122114": {
      A: "BEL",
      B: "g",
      C: "71"
    },
    "122411": {
      A: "BS",
      B: "h",
      C: "72"
    },
    "142112": {
      A: "HT",
      B: "i",
      C: "73"
    },
    "142211": {
      A: "LF",
      B: "j",
      C: "74"
    },
    "241211": {
      A: "VT",
      B: "k",
      C: "75"
    },
    "221114": {
      A: "FF",
      B: "l",
      C: "76"
    },
    "413111": {
      A: "CR",
      B: "m",
      C: "77"
    },
    "241112": {
      A: "SO",
      B: "n",
      C: "78"
    },
    "134111": {
      A: "SI",
      B: "o",
      C: "79"
    },
    "111242": {
      A: "DLE",
      B: "p",
      C: "80"
    },
    "121142": {
      A: "DC1",
      B: "q",
      C: "81"
    },
    "121241": {
      A: "DC2",
      B: "r",
      C: "82"
    },
    "114212": {
      A: "DC3",
      B: "s",
      C: "83"
    },
    "124112": {
      A: "DC4",
      B: "t",
      C: "84"
    },
    "124211": {
      A: "NAK",
      B: "u",
      C: "85"
    },
    "411212": {
      A: "SYN",
      B: "v",
      C: "86"
    },
    "421112": {
      A: "ETB",
      B: "w",
      C: "87"
    },
    "421211": {
      A: "CAN",
      B: "x",
      C: "88"
    },
    "212141": {
      A: "EM",
      B: "y",
      C: "89"
    },
    "214121": {
      A: "SUB",
      B: "z",
      C: "90"
    },
    "412121": {
      A: "ESC",
      B: "{",
      C: "91"
    },
    "111143": {
      A: "FS",
      B: "|",
      C: "92"
    },
    "111341": {
      A: "GS",
      B: "}",
      C: "93"
    },
    "131141": {
      A: "RS",
      B: "~",
      C: "94"
    },
    "114113": {
      A: "US",
      B: "DEL",
      C: "95"
    },
    "114311": {
      A: "FNC3",
      B: "FNC3",
      C: "96"
    },
    "411113": {
      A: "FNC2",
      B: "FNC2",
      C: "97"
    },
    "411311": {
      A: "SHIFT_B",
      B: "SHIFT_A",
      C: "98"
    },
    "113141": {
      A: "Code_C",
      B: "Code_C",
      C: "99"
    },
    "114131": {
      A: "Code_B",
      B: "FNC4",
      C: "Code_B"
    },
    "311141": {
      A: "FNC4",
      B: "Code_A",
      C: "Code_A"
    },
    "411131": {
      A: "FNC1",
      B: "FNC1",
      C: "FNC1"
    },
    "211412": "A",
    "211214": "B",
    "211232": "C",
    "233111": {
      A: "STOP",
      B: "STOP",
      C: "STOP"
    },
    value: [
      "212222",
      "222122",
      "222221",
      "121223",
      "121322",
      "131222",
      "122213",
      "122312",
      "132212",
      "221213",
      "221312",
      "231212",
      "112232",
      "122132",
      "122231",
      "113222",
      "123122",
      "123221",
      "223211",
      "221132",
      "221231",
      "213212",
      "223112",
      "312131",
      "311222",
      "321122",
      "321221",
      "312212",
      "322112",
      "322211",
      "212123",
      "212321",
      "232121",
      "111323",
      "131123",
      "131321",
      "112313",
      "132113",
      "132311",
      "211313",
      "231113",
      "231311",
      "112133",
      "112331",
      "132131",
      "113123",
      "113321",
      "133121",
      "313121",
      "211331",
      "231131",
      "213113",
      "213311",
      "213131",
      "311123",
      "311321",
      "331121",
      "312113",
      "312311",
      "332111",
      "314111",
      "221411",
      "431111",
      "111224",
      "111422",
      "121124",
      "121421",
      "141122",
      "141221",
      "112214",
      "112412",
      "122114",
      "122411",
      "142112",
      "142211",
      "241211",
      "221114",
      "413111",
      "241112",
      "134111",
      "111242",
      "121142",
      "121241",
      "114212",
      "124112",
      "124211",
      "411212",
      "421112",
      "421211",
      "212141",
      "214121",
      "412121",
      "111143",
      "111341",
      "131141",
      "114113",
      "114311",
      "411113",
      "411311",
      "113141",
      "114131",
      "311141",
      "411131",
      "211412",
      "211214",
      "211232",
      "233111"
    ]
  };
 
  Code93Encoding = {
    "131112": {
      value: 0,
      character: "0"
    },
    "111213": {
      value: 1,
      character: "1"
    },
    "111312": {
      value: 2,
      character: "2"
    },
    "111411": {
      value: 3,
      character: "3"
    },
    "121113": {
      value: 4,
      character: "4"
    },
    "121212": {
      value: 5,
      character: "5"
    },
    "121311": {
      value: 6,
      character: "6"
    },
    "111114": {
      value: 7,
      character: "7"
    },
    "131211": {
      value: 8,
      character: "8"
    },
    "141111": {
      value: 9,
      character: "9"
    },
    "211113": {
      value: 10,
      character: "A"
    },
    "211212": {
      value: 11,
      character: "B"
    },
    "211311": {
      value: 12,
      character: "C"
    },
    "221112": {
      value: 13,
      character: "D"
    },
    "221211": {
      value: 14,
      character: "E"
    },
    "231111": {
      value: 15,
      character: "F"
    },
    "112113": {
      value: 16,
      character: "G"
    },
    "112212": {
      value: 17,
      character: "H"
    },
    "112311": {
      value: 18,
      character: "I"
    },
    "122112": {
      value: 19,
      character: "J"
    },
    "132111": {
      value: 20,
      character: "K"
    },
    "111123": {
      value: 21,
      character: "L"
    },
    "111222": {
      value: 22,
      character: "M"
    },
    "111321": {
      value: 23,
      character: "N"
    },
    "121122": {
      value: 24,
      character: "O"
    },
    "131121": {
      value: 25,
      character: "P"
    },
    "212112": {
      value: 26,
      character: "Q"
    },
    "212211": {
      value: 27,
      character: "R"
    },
    "211122": {
      value: 28,
      character: "S"
    },
    "211221": {
      value: 29,
      character: "T"
    },
    "221121": {
      value: 30,
      character: "U"
    },
    "222111": {
      value: 31,
      character: "V"
    },
    "112122": {
      value: 32,
      character: "W"
    },
    "112221": {
      value: 33,
      character: "X"
    },
    "122121": {
      value: 34,
      character: "Y"
    },
    "123111": {
      value: 35,
      character: "Z"
    },
    "121131": {
      value: 36,
      character: "-"
    },
    "311112": {
      value: 37,
      character: "."
    },
    "311211": {
      value: 38,
      character: " "
    },
    "321111": {
      value: 39,
      character: "$"
    },
    "112131": {
      value: 40,
      character: "/"
    },
    "113121": {
      value: 41,
      character: "+"
    },
    "211131": {
      value: 42,
      character: "%"
    },
    "121221": {
      value: 43,
      character: "($)"
    },
    "312111": {
      value: 44,
      character: "(%)"
    },
    "311121": {
      value: 45,
      character: "(/)"
    },
    "122211": {
      value: 46,
      character: "(+)"
    },
    "111141": {
      value: -1,
      character: "*"
    }
  };
  Code39Encoding = {
    "111221211": {
      value: 0,
      character: "0"
    },
    "211211112": {
      value: 1,
      character: "1"
    },
    "112211112": {
      value: 2,
      character: "2"
    },
    "212211111": {
      value: 3,
      character: "3"
    },
    "111221112": {
      value: 4,
      character: "4"
    },
    "211221111": {
      value: 5,
      character: "5"
    },
    "112221111": {
      value: 6,
      character: "6"
    },
    "111211212": {
      value: 7,
      character: "7"
    },
    "211211211": {
      value: 8,
      character: "8"
    },
    "112211211": {
      value: 9,
      character: "9"
    },
    "211112112": {
      value: 10,
      character: "A"
    },
    "112112112": {
      value: 11,
      character: "B"
    },
    "212112111": {
      value: 12,
      character: "C"
    },
    "111122112": {
      value: 13,
      character: "D"
    },
    "211122111": {
      value: 14,
      character: "E"
    },
    "112122111": {
      value: 15,
      character: "F"
    },
    "111112212": {
      value: 16,
      character: "G"
    },
    "211112211": {
      value: 17,
      character: "H"
    },
    "112112211": {
      value: 18,
      character: "I"
    },
    "111122211": {
      value: 19,
      character: "J"
    },
    "211111122": {
      value: 20,
      character: "K"
    },
    "112111122": {
      value: 21,
      character: "L"
    },
    "212111121": {
      value: 22,
      character: "M"
    },
    "111121122": {
      value: 23,
      character: "N"
    },
    "211121121": {
      value: 24,
      character: "O"
    },
    "112121121": {
      value: 25,
      character: "P"
    },
    "111111222": {
      value: 26,
      character: "Q"
    },
    "211111221": {
      value: 27,
      character: "R"
    },
    "112111221": {
      value: 28,
      character: "S"
    },
    "111121221": {
      value: 29,
      character: "T"
    },
    "221111112": {
      value: 30,
      character: "U"
    },
    "122111112": {
      value: 31,
      character: "V"
    },
    "222111111": {
      value: 32,
      character: "W"
    },
    "121121112": {
      value: 33,
      character: "X"
    },
    "221121111": {
      value: 34,
      character: "Y"
    },
    "122121111": {
      value: 35,
      character: "Z"
    },
    "121111212": {
      value: 36,
      character: "-"
    },
    "221111211": {
      value: 37,
      character: "."
    },
    "122111211": {
      value: 38,
      character: " "
    },
    "121212111": {
      value: 39,
      character: "$"
    },
    "121211121": {
      value: 40,
      character: "/"
    },
    "121112121": {
      value: 41,
      character: "+"
    },
    "111212121": {
      value: 42,
      character: "%"
    },
    "121121211": {
      value: -1,
      character: "*"
    }
  };
 
  ExtendedEncoding = {
    "/A": '!',
    "/B": '"',
    "/C": '#',
    "/D": '$',
    "/E": '%',
    "/F": '&',
    "/G": "'",
    "/H": '(',
    "/I": ')',
    "/J": '*',
    "/K": '+',
    "/L": ',',
    "/O": '/',
    "/Z": ':',
    "%F": ';',
    "%G": '<',
    "%H": '=',
    "%I": '>',
    "%J": '?',
    "%K": '[',
    "%L": "\\",
    "%M": ']',
    "%N": '^',
    "%O": '_',
    "+A": 'a',
    "+B": 'b',
    "+C": 'c',
    "+D": 'd',
    "+E": 'e',
    "+F": 'f',
    "+G": 'g',
    "+H": 'h',
    "+I": 'i',
    "+J": 'j',
    "+K": 'k',
    "+L": 'l',
    "+M": 'm',
    "+N": 'n',
    "+O": 'o',
    "+P": 'p',
    "+Q": 'q',
    "+R": 'r',
    "+S": 's',
    "+T": 't',
    "+U": 'u',
    "+V": 'v',
    "+W": 'w',
    "+X": 'x',
    "+Y": 'y',
    "+Z": 'z',
    "%P": "{",
    "%Q": '|',
    "%R": '|',
    "%S": '~',
  };
 
  CodaBarEncoding = {
    "0000011": "0",
    "0000110": "1",
    "0001001": "2",
    "1100000": "3",
    "0010010": "4",
    "1000010": "5",
    "0100001": "6",
    "0100100": "7",
    "0110000": "8",
    "1001000": "9",
    "0001100": "-",
    "0011000": "$",
    "1000101": ":",
    "1010001": "/",
    "1010100": ".",
    "0011111": "+",
    "0011010": "A",
    "0001011": "B",
    "0101001": "C",
    "0001110": "D"
  };
 
  EAN13Encoding = {
    "L": {
      "3211": 0,
      "2221": 1,
      "2122": 2,
      "1411": 3,
      "1132": 4,
      "1231": 5,
      "1114": 6,
      "1312": 7,
      "1213": 8,
      "3112": 9
    },
    "G": {
      "1123": 0,
      "1222": 1,
      "2212": 2,
      "1141": 3,
      "2311": 4,
      "1321": 5,
      "4111": 6,
      "2131": 7,
      "3121": 8,
      "2113": 9
    },
    "R": {
      "3211": 0,
      "2221": 1,
      "2122": 2,
      "1411": 3,
      "1132": 4,
      "1231": 5,
      "1114": 6,
      "1312": 7,
      "1213": 8,
      "3112": 9
    },
    formats: {
      "LLLLLL": 0,
      "LLGLGG": 1,
      "LLGGLG": 2,
      "LLGGGL": 3,
      "LGLLGG": 4,
      "LGGLLG": 5,
      "LGGGLL": 6,
      "LGLGLG": 7,
      "LGLGGL": 8,
      "LGGLGL": 9
    }
  };
 
  self.onmessage = function(e) {
    var width;
    var i;
 
    ScanImage = {
      data: new Uint8ClampedArray(e.data.scan),
      width: e.data.scanWidth,
      height: e.data.scanHeight
    };
    switch (e.data.rotation) {
      case 8:
        ScanImage.data = Rotate(ScanImage.data, ScanImage.width, ScanImage.height, -90);
        width = e.data.scanWidth;
        ScanImage.width = ScanImage.height;
        ScanImage.height = width;
        break;
      case 6:
        ScanImage.data = Rotate(ScanImage.data, ScanImage.width, ScanImage.height, 90);
        width = e.data.scanWidth;
        ScanImage.width = ScanImage.height;
        ScanImage.height = width;
        break;
      case 3:
        ScanImage.data = Rotate(ScanImage.data, ScanImage.width, ScanImage.height, 180);
    }
    Image = {
      data: Scale(ScanImage.data, ScanImage.width, ScanImage.height),
      width: ScanImage.width / 2,
      height: ScanImage.height / 2
    };
    if (e.data.postOrientation) {
      postMessage({
        result: Image,
        success: "orientationData"
      });
    }
    availableFormats = ["Code128", "Code93", "Code39", "EAN-13", "2Of5", "Inter2Of5", "Codabar"];
    FormatPriority = [];
    var decodeFormats = ["Code128", "Code93", "Code39", "EAN-13", "2Of5", "Inter2Of5", "Codabar"];
    SecureCodabar = true;
    Secure2Of5 = true;
    Multiple = true;
    if (typeof e.data.multiple !== 'undefined') {
      Multiple = e.data.multiple;
    }
    if (typeof e.data.decodeFormats !== 'undefined') {
      decodeFormats = e.data.decodeFormats;
    }
    for (i = 0; i < decodeFormats.length; i++) {
      FormatPriority.push(decodeFormats[i]);
    }
    CreateTable();
    CreateScanTable();
    var FinalResult = Main();
    if (FinalResult.length > 0) {
      postMessage({
        result: FinalResult,
        success: true
      });
    } else {
      postMessage({
        result: FinalResult,
        success: false
      });
    }
  };
};
 
var decoderWorkerBlobString = decoderWorkerBlob.toString();
decoderWorkerBlobString = decoderWorkerBlobString.substring(decoderWorkerBlobString.indexOf("{")+1, decoderWorkerBlobString.lastIndexOf("}"));
 
if (typeof exports !== 'undefined') {
  if (typeof module !== 'undefined' && module.exports) {
      exports = module.exports = decoderWorkerBlobString;
  }
  exports.decoderWorkerBlobString = decoderWorkerBlobString;
} else {
  root.decoderWorkerBlobString = decoderWorkerBlobString;
}
},{}],3:[function(require,module,exports){
(function() {
 
  var debug = false;
 
  var root = this;
 
  var EXIF = function(obj) {
    if (obj instanceof EXIF) return obj;
    if (!(this instanceof EXIF)) return new EXIF(obj);
    this.EXIFwrapped = obj;
  };
 
  if (typeof exports !== 'undefined') {
    if (typeof module !== 'undefined' && module.exports) {
      exports = module.exports = EXIF;
    }
    exports.EXIF = EXIF;
  } else {
    root.EXIF = EXIF;
  }
 
  var ExifTags = EXIF.Tags = {
 
    // version tags
    0x9000: "ExifVersion", // EXIF version
    0xA000: "FlashpixVersion", // Flashpix format version
 
    // colorspace tags
    0xA001: "ColorSpace", // Color space information tag
 
    // image configuration
    0xA002: "PixelXDimension", // Valid width of meaningful image
    0xA003: "PixelYDimension", // Valid height of meaningful image
    0x9101: "ComponentsConfiguration", // Information about channels
    0x9102: "CompressedBitsPerPixel", // Compressed bits per pixel
 
    // user information
    0x927C: "MakerNote", // Any desired information written by the manufacturer
    0x9286: "UserComment", // Comments by user
 
    // related file
    0xA004: "RelatedSoundFile", // Name of related sound file
 
    // date and time
    0x9003: "DateTimeOriginal", // Date and time when the original image was generated
    0x9004: "DateTimeDigitized", // Date and time when the image was stored digitally
    0x9290: "SubsecTime", // Fractions of seconds for DateTime
    0x9291: "SubsecTimeOriginal", // Fractions of seconds for DateTimeOriginal
    0x9292: "SubsecTimeDigitized", // Fractions of seconds for DateTimeDigitized
 
    // picture-taking conditions
    0x829A: "ExposureTime", // Exposure time (in seconds)
    0x829D: "FNumber", // F number
    0x8822: "ExposureProgram", // Exposure program
    0x8824: "SpectralSensitivity", // Spectral sensitivity
    0x8827: "ISOSpeedRatings", // ISO speed rating
    0x8828: "OECF", // Optoelectric conversion factor
    0x9201: "ShutterSpeedValue", // Shutter speed
    0x9202: "ApertureValue", // Lens aperture
    0x9203: "BrightnessValue", // Value of brightness
    0x9204: "ExposureBias", // Exposure bias
    0x9205: "MaxApertureValue", // Smallest F number of lens
    0x9206: "SubjectDistance", // Distance to subject in meters
    0x9207: "MeteringMode", // Metering mode
    0x9208: "LightSource", // Kind of light source
    0x9209: "Flash", // Flash status
    0x9214: "SubjectArea", // Location and area of main subject
    0x920A: "FocalLength", // Focal length of the lens in mm
    0xA20B: "FlashEnergy", // Strobe energy in BCPS
    0xA20C: "SpatialFrequencyResponse", //
    0xA20E: "FocalPlaneXResolution", // Number of pixels in width direction per FocalPlaneResolutionUnit
    0xA20F: "FocalPlaneYResolution", // Number of pixels in height direction per FocalPlaneResolutionUnit
    0xA210: "FocalPlaneResolutionUnit", // Unit for measuring FocalPlaneXResolution and FocalPlaneYResolution
    0xA214: "SubjectLocation", // Location of subject in image
    0xA215: "ExposureIndex", // Exposure index selected on camera
    0xA217: "SensingMethod", // Image sensor type
    0xA300: "FileSource", // Image source (3 == DSC)
    0xA301: "SceneType", // Scene type (1 == directly photographed)
    0xA302: "CFAPattern", // Color filter array geometric pattern
    0xA401: "CustomRendered", // Special processing
    0xA402: "ExposureMode", // Exposure mode
    0xA403: "WhiteBalance", // 1 = auto white balance, 2 = manual
    0xA404: "DigitalZoomRation", // Digital zoom ratio
    0xA405: "FocalLengthIn35mmFilm", // Equivalent foacl length assuming 35mm film camera (in mm)
    0xA406: "SceneCaptureType", // Type of scene
    0xA407: "GainControl", // Degree of overall image gain adjustment
    0xA408: "Contrast", // Direction of contrast processing applied by camera
    0xA409: "Saturation", // Direction of saturation processing applied by camera
    0xA40A: "Sharpness", // Direction of sharpness processing applied by camera
    0xA40B: "DeviceSettingDescription", //
    0xA40C: "SubjectDistanceRange", // Distance to subject
 
    // other tags
    0xA005: "InteroperabilityIFDPointer",
    0xA420: "ImageUniqueID" // Identifier assigned uniquely to each image
  };
 
  var TiffTags = EXIF.TiffTags = {
    0x0100: "ImageWidth",
    0x0101: "ImageHeight",
    0x8769: "ExifIFDPointer",
    0x8825: "GPSInfoIFDPointer",
    0xA005: "InteroperabilityIFDPointer",
    0x0102: "BitsPerSample",
    0x0103: "Compression",
    0x0106: "PhotometricInterpretation",
    0x0112: "Orientation",
    0x0115: "SamplesPerPixel",
    0x011C: "PlanarConfiguration",
    0x0212: "YCbCrSubSampling",
    0x0213: "YCbCrPositioning",
    0x011A: "XResolution",
    0x011B: "YResolution",
    0x0128: "ResolutionUnit",
    0x0111: "StripOffsets",
    0x0116: "RowsPerStrip",
    0x0117: "StripByteCounts",
    0x0201: "JPEGInterchangeFormat",
    0x0202: "JPEGInterchangeFormatLength",
    0x012D: "TransferFunction",
    0x013E: "WhitePoint",
    0x013F: "PrimaryChromaticities",
    0x0211: "YCbCrCoefficients",
    0x0214: "ReferenceBlackWhite",
    0x0132: "DateTime",
    0x010E: "ImageDescription",
    0x010F: "Make",
    0x0110: "Model",
    0x0131: "Software",
    0x013B: "Artist",
    0x8298: "Copyright"
  };
 
  var GPSTags = EXIF.GPSTags = {
    0x0000: "GPSVersionID",
    0x0001: "GPSLatitudeRef",
    0x0002: "GPSLatitude",
    0x0003: "GPSLongitudeRef",
    0x0004: "GPSLongitude",
    0x0005: "GPSAltitudeRef",
    0x0006: "GPSAltitude",
    0x0007: "GPSTimeStamp",
    0x0008: "GPSSatellites",
    0x0009: "GPSStatus",
    0x000A: "GPSMeasureMode",
    0x000B: "GPSDOP",
    0x000C: "GPSSpeedRef",
    0x000D: "GPSSpeed",
    0x000E: "GPSTrackRef",
    0x000F: "GPSTrack",
    0x0010: "GPSImgDirectionRef",
    0x0011: "GPSImgDirection",
    0x0012: "GPSMapDatum",
    0x0013: "GPSDestLatitudeRef",
    0x0014: "GPSDestLatitude",
    0x0015: "GPSDestLongitudeRef",
    0x0016: "GPSDestLongitude",
    0x0017: "GPSDestBearingRef",
    0x0018: "GPSDestBearing",
    0x0019: "GPSDestDistanceRef",
    0x001A: "GPSDestDistance",
    0x001B: "GPSProcessingMethod",
    0x001C: "GPSAreaInformation",
    0x001D: "GPSDateStamp",
    0x001E: "GPSDifferential"
  };
 
  var StringValues = EXIF.StringValues = {
    ExposureProgram: {
      0: "Not defined",
      1: "Manual",
      2: "Normal program",
      3: "Aperture priority",
      4: "Shutter priority",
      5: "Creative program",
      6: "Action program",
      7: "Portrait mode",
      8: "Landscape mode"
    },
    MeteringMode: {
      0: "Unknown",
      1: "Average",
      2: "CenterWeightedAverage",
      3: "Spot",
      4: "MultiSpot",
      5: "Pattern",
      6: "Partial",
      255: "Other"
    },
    LightSource: {
      0: "Unknown",
      1: "Daylight",
      2: "Fluorescent",
      3: "Tungsten (incandescent light)",
      4: "Flash",
      9: "Fine weather",
      10: "Cloudy weather",
      11: "Shade",
      12: "Daylight fluorescent (D 5700 - 7100K)",
      13: "Day white fluorescent (N 4600 - 5400K)",
      14: "Cool white fluorescent (W 3900 - 4500K)",
      15: "White fluorescent (WW 3200 - 3700K)",
      17: "Standard light A",
      18: "Standard light B",
      19: "Standard light C",
      20: "D55",
      21: "D65",
      22: "D75",
      23: "D50",
      24: "ISO studio tungsten",
      255: "Other"
    },
    Flash: {
      0x0000: "Flash did not fire",
      0x0001: "Flash fired",
      0x0005: "Strobe return light not detected",
      0x0007: "Strobe return light detected",
      0x0009: "Flash fired, compulsory flash mode",
      0x000D: "Flash fired, compulsory flash mode, return light not detected",
      0x000F: "Flash fired, compulsory flash mode, return light detected",
      0x0010: "Flash did not fire, compulsory flash mode",
      0x0018: "Flash did not fire, auto mode",
      0x0019: "Flash fired, auto mode",
      0x001D: "Flash fired, auto mode, return light not detected",
      0x001F: "Flash fired, auto mode, return light detected",
      0x0020: "No flash function",
      0x0041: "Flash fired, red-eye reduction mode",
      0x0045: "Flash fired, red-eye reduction mode, return light not detected",
      0x0047: "Flash fired, red-eye reduction mode, return light detected",
      0x0049: "Flash fired, compulsory flash mode, red-eye reduction mode",
      0x004D: "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
      0x004F: "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
      0x0059: "Flash fired, auto mode, red-eye reduction mode",
      0x005D: "Flash fired, auto mode, return light not detected, red-eye reduction mode",
      0x005F: "Flash fired, auto mode, return light detected, red-eye reduction mode"
    },
    SensingMethod: {
      1: "Not defined",
      2: "One-chip color area sensor",
      3: "Two-chip color area sensor",
      4: "Three-chip color area sensor",
      5: "Color sequential area sensor",
      7: "Trilinear sensor",
      8: "Color sequential linear sensor"
    },
    SceneCaptureType: {
      0: "Standard",
      1: "Landscape",
      2: "Portrait",
      3: "Night scene"
    },
    SceneType: {
      1: "Directly photographed"
    },
    CustomRendered: {
      0: "Normal process",
      1: "Custom process"
    },
    WhiteBalance: {
      0: "Auto white balance",
      1: "Manual white balance"
    },
    GainControl: {
      0: "None",
      1: "Low gain up",
      2: "High gain up",
      3: "Low gain down",
      4: "High gain down"
    },
    Contrast: {
      0: "Normal",
      1: "Soft",
      2: "Hard"
    },
    Saturation: {
      0: "Normal",
      1: "Low saturation",
      2: "High saturation"
    },
    Sharpness: {
      0: "Normal",
      1: "Soft",
      2: "Hard"
    },
    SubjectDistanceRange: {
      0: "Unknown",
      1: "Macro",
      2: "Close view",
      3: "Distant view"
    },
    FileSource: {
      3: "DSC"
    },
 
    Components: {
      0: "",
      1: "Y",
      2: "Cb",
      3: "Cr",
      4: "R",
      5: "G",
      6: "B"
    }
  };
 
  function addEvent(element, event, handler) {
    if (element.addEventListener) {
      element.addEventListener(event, handler, false);
    } else if (element.attachEvent) {
      element.attachEvent("on" + event, handler);
    }
  }
 
  function imageHasData(img) {
    return !!(img.exifdata);
  }
 
 
  function base64ToArrayBuffer(base64, contentType) {
    contentType = contentType || base64.match(/^data\:([^\;]+)\;base64,/mi)[1] || ''; // e.g. 'data:image/jpeg;base64,...' => 'image/jpeg'
    base64 = base64.replace(/^data\:([^\;]+)\;base64,/gmi, '');
    var binary = atob(base64);
    var len = binary.length;
    var buffer = new ArrayBuffer(len);
    var view = new Uint8Array(buffer);
    for (var i = 0; i < len; i++) {
      view[i] = binary.charCodeAt(i);
    }
    return buffer;
  }
 
  function objectURLToBlob(url, callback) {
    var http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.responseType = "blob";
    http.onload = function(e) {
      if (this.status == 200 || this.status === 0) {
        callback(this.response);
      }
    };
    http.send();
  }
 
  function getImageData(img, callback) {
    var fileReader = new FileReader();
    var handleBinaryFile = function handleBinaryFile(binFile) {
      var data = findEXIFinJPEG(binFile);
      var iptcdata = findIPTCinJPEG(binFile);
      img.exifdata = data || {};
      img.iptcdata = iptcdata || {};
      if (callback) {
        callback(img);
      }
    };
 
    if (img.src) {
      if (/^data\:/i.test(img.src)) { // Data URI
        var arrayBuffer = base64ToArrayBuffer(img.src);
        handleBinaryFile(arrayBuffer);
 
      } else if (/^blob\:/i.test(img.src)) { // Object URL
        fileReader.onload = function(e) {
          handleBinaryFile(e.target.result);
        };
        objectURLToBlob(img.src, function(blob) {
          fileReader.readAsArrayBuffer(blob);
        });
      } else {
        var http = new XMLHttpRequest();
        http.onload = function() {
          if (this.status == 200 || this.status === 0) {
            handleBinaryFile(http.response);
          } else {
            throw "Could not load image";
          }
          http = null;
        };
        http.open("GET", img.src, true);
        http.responseType = "arraybuffer";
        http.send(null);
      }
    } else if (window.FileReader && (img instanceof window.Blob || img instanceof window.File)) {
      fileReader.onload = function(e) {
        if (debug) console.log("Got file of length " + e.target.result.byteLength);
        handleBinaryFile(e.target.result);
      };
 
      fileReader.readAsArrayBuffer(img);
    }
  }
 
  function findEXIFinJPEG(file) {
    var dataView = new DataView(file);
 
    if (debug) console.log("Got file of length " + file.byteLength);
    if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
      if (debug) console.log("Not a valid JPEG");
      return false; // not a valid jpeg
    }
 
    var offset = 2,
      length = file.byteLength,
      marker;
 
    while (offset < length) {
      if (dataView.getUint8(offset) != 0xFF) {
        if (debug) console.log("Not a valid marker at offset " + offset + ", found: " + dataView.getUint8(offset));
        return false; // not a valid marker, something is wrong
      }
 
      marker = dataView.getUint8(offset + 1);
      if (debug) console.log(marker);
 
      // we could implement handling for other markers here,
      // but we're only looking for 0xFFE1 for EXIF data
 
      if (marker == 225) {
        if (debug) console.log("Found 0xFFE1 marker");
 
        return readEXIFData(dataView, offset + 4, dataView.getUint16(offset + 2) - 2);
 
        // offset += 2 + file.getShortAt(offset+2, true);
 
      } else {
        offset += 2 + dataView.getUint16(offset + 2);
      }
 
    }
 
  }
 
  function findIPTCinJPEG(file) {
    var dataView = new DataView(file);
 
    if (debug) console.log("Got file of length " + file.byteLength);
    if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
      if (debug) console.log("Not a valid JPEG");
      return false; // not a valid jpeg
    }
 
    var offset = 2,
      length = file.byteLength;
 
 
    var isFieldSegmentStart = function(dataView, offset) {
      return (
        dataView.getUint8(offset) === 0x38 &&
        dataView.getUint8(offset + 1) === 0x42 &&
        dataView.getUint8(offset + 2) === 0x49 &&
        dataView.getUint8(offset + 3) === 0x4D &&
        dataView.getUint8(offset + 4) === 0x04 &&
        dataView.getUint8(offset + 5) === 0x04
      );
    };
 
    while (offset < length) {
 
      if (isFieldSegmentStart(dataView, offset)) {
 
        // Get the length of the name header (which is padded to an even number of bytes)
        var nameHeaderLength = dataView.getUint8(offset + 7);
        if (nameHeaderLength % 2 !== 0) nameHeaderLength += 1;
        // Check for pre photoshop 6 format
        if (nameHeaderLength === 0) {
          // Always 4
          nameHeaderLength = 4;
        }
 
        var startOffset = offset + 8 + nameHeaderLength;
        var sectionLength = dataView.getUint16(offset + 6 + nameHeaderLength);
 
        return readIPTCData(file, startOffset, sectionLength);
      }
 
 
      // Not the marker, continue searching
      offset++;
 
    }
 
  }
  var IptcFieldMap = {
    0x78: 'caption',
    0x6E: 'credit',
    0x19: 'keywords',
    0x37: 'dateCreated',
    0x50: 'byline',
    0x55: 'bylineTitle',
    0x7A: 'captionWriter',
    0x69: 'headline',
    0x74: 'copyright',
    0x0F: 'category'
  };
 
  function readIPTCData(file, startOffset, sectionLength) {
    var dataView = new DataView(file);
    var data = {};
    var fieldValue, fieldName, dataSize, segmentType, segmentSize;
    var segmentStartPos = startOffset;
    while (segmentStartPos < startOffset + sectionLength) {
      if (dataView.getUint8(segmentStartPos) === 0x1C && dataView.getUint8(segmentStartPos + 1) === 0x02) {
        segmentType = dataView.getUint8(segmentStartPos + 2);
        if (segmentType in IptcFieldMap) {
          dataSize = dataView.getInt16(segmentStartPos + 3);
          segmentSize = dataSize + 5;
          fieldName = IptcFieldMap[segmentType];
          fieldValue = getStringFromDB(dataView, segmentStartPos + 5, dataSize);
          // Check if we already stored a value with this name
          if (data.hasOwnProperty(fieldName)) {
            // Value already stored with this name, create multivalue field
            if (data[fieldName] instanceof Array) {
              data[fieldName].push(fieldValue);
            } else {
              data[fieldName] = [data[fieldName], fieldValue];
            }
          } else {
            data[fieldName] = fieldValue;
          }
        }
 
      }
      segmentStartPos++;
    }
    return data;
  }
 
 
 
  function readTags(file, tiffStart, dirStart, strings, bigEnd) {
    var entries = file.getUint16(dirStart, !bigEnd),
      tags = {},
      entryOffset, tag,
      i;
 
    for (i = 0; i < entries; i++) {
      entryOffset = dirStart + i * 12 + 2;
      tag = strings[file.getUint16(entryOffset, !bigEnd)];
      if (!tag && debug) console.log("Unknown tag: " + file.getUint16(entryOffset, !bigEnd));
      tags[tag] = readTagValue(file, entryOffset, tiffStart, dirStart, bigEnd);
    }
    return tags;
  }
 
 
  function readTagValue(file, entryOffset, tiffStart, dirStart, bigEnd) {
    var type = file.getUint16(entryOffset + 2, !bigEnd),
      numValues = file.getUint32(entryOffset + 4, !bigEnd),
      valueOffset = file.getUint32(entryOffset + 8, !bigEnd) + tiffStart,
      offset,
      vals, val, n,
      numerator, denominator;
 
    switch (type) {
      case 1: // byte, 8-bit unsigned int
      case 7: // undefined, 8-bit byte, value depending on field
        if (numValues == 1) {
          return file.getUint8(entryOffset + 8, !bigEnd);
        } else {
          offset = numValues > 4 ? valueOffset : (entryOffset + 8);
          vals = [];
          for (n = 0; n < numValues; n++) {
            vals[n] = file.getUint8(offset + n);
          }
          return vals;
        }
        break;
      case 2: // ascii, 8-bit byte
        offset = numValues > 4 ? valueOffset : (entryOffset + 8);
        return getStringFromDB(file, offset, numValues - 1);
 
      case 3: // short, 16 bit int
        if (numValues == 1) {
          return file.getUint16(entryOffset + 8, !bigEnd);
        } else {
          offset = numValues > 2 ? valueOffset : (entryOffset + 8);
          vals = [];
          for (n = 0; n < numValues; n++) {
            vals[n] = file.getUint16(offset + 2 * n, !bigEnd);
          }
          return vals;
        }
        break;
      case 4: // long, 32 bit int
        if (numValues == 1) {
          return file.getUint32(entryOffset + 8, !bigEnd);
        } else {
          vals = [];
          for (n = 0; n < numValues; n++) {
            vals[n] = file.getUint32(valueOffset + 4 * n, !bigEnd);
          }
          return vals;
        }
        break;
      case 5: // rational = two long values, first is numerator, second is denominator
        if (numValues == 1) {
          numerator = file.getUint32(valueOffset, !bigEnd);
          denominator = file.getUint32(valueOffset + 4, !bigEnd);
          val = numerator / denominator;
          val.numerator = numerator;
          val.denominator = denominator;
          return val;
        } else {
          vals = [];
          for (n = 0; n < numValues; n++) {
            numerator = file.getUint32(valueOffset + 8 * n, !bigEnd);
            denominator = file.getUint32(valueOffset + 4 + 8 * n, !bigEnd);
            vals[n] = numerator / denominator;
            vals[n].numerator = numerator;
            vals[n].denominator = denominator;
          }
          return vals;
        }
        break;
      case 9: // slong, 32 bit signed int
        if (numValues == 1) {
          return file.getInt32(entryOffset + 8, !bigEnd);
        } else {
          vals = [];
          for (n = 0; n < numValues; n++) {
            vals[n] = file.getInt32(valueOffset + 4 * n, !bigEnd);
          }
          return vals;
        }
        break;
      case 10: // signed rational, two slongs, first is numerator, second is denominator
        if (numValues == 1) {
          return file.getInt32(valueOffset, !bigEnd) / file.getInt32(valueOffset + 4, !bigEnd);
        } else {
          vals = [];
          for (n = 0; n < numValues; n++) {
            vals[n] = file.getInt32(valueOffset + 8 * n, !bigEnd) / file.getInt32(valueOffset + 4 + 8 * n, !bigEnd);
          }
          return vals;
        }
    }
  }
 
  function getStringFromDB(buffer, start, length) {
    var outstr = "";
    var n;
    for (n = start; n < start + length; n++) {
      outstr += String.fromCharCode(buffer.getUint8(n));
    }
    return outstr;
  }
 
  function readEXIFData(file, start) {
    if (getStringFromDB(file, start, 4) != "Exif") {
      if (debug) console.log("Not valid EXIF data! " + getStringFromDB(file, start, 4));
      return false;
    }
 
    var bigEnd,
      tags, tag,
      exifData, gpsData,
      tiffOffset = start + 6;
 
    // test for TIFF validity and endianness
    if (file.getUint16(tiffOffset) == 0x4949) {
      bigEnd = false;
    } else if (file.getUint16(tiffOffset) == 0x4D4D) {
      bigEnd = true;
    } else {
      if (debug) console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)");
      return false;
    }
 
    if (file.getUint16(tiffOffset + 2, !bigEnd) != 0x002A) {
      if (debug) console.log("Not valid TIFF data! (no 0x002A)");
      return false;
    }
 
    var firstIFDOffset = file.getUint32(tiffOffset + 4, !bigEnd);
 
    if (firstIFDOffset < 0x00000008) {
      if (debug) console.log("Not valid TIFF data! (First offset less than 8)", file.getUint32(tiffOffset + 4, !bigEnd));
      return false;
    }
 
    tags = readTags(file, tiffOffset, tiffOffset + firstIFDOffset, TiffTags, bigEnd);
 
    if (tags.ExifIFDPointer) {
      exifData = readTags(file, tiffOffset, tiffOffset + tags.ExifIFDPointer, ExifTags, bigEnd);
      for (tag in exifData) {
        switch (tag) {
          case "LightSource":
          case "Flash":
          case "MeteringMode":
          case "ExposureProgram":
          case "SensingMethod":
          case "SceneCaptureType":
          case "SceneType":
          case "CustomRendered":
          case "WhiteBalance":
          case "GainControl":
          case "Contrast":
          case "Saturation":
          case "Sharpness":
          case "SubjectDistanceRange":
          case "FileSource":
            exifData[tag] = StringValues[tag][exifData[tag]];
            break;
 
          case "ExifVersion":
          case "FlashpixVersion":
            exifData[tag] = String.fromCharCode(exifData[tag][0], exifData[tag][1], exifData[tag][2], exifData[tag][3]);
            break;
 
          case "ComponentsConfiguration":
            exifData[tag] =
              StringValues.Components[exifData[tag][0]] +
              StringValues.Components[exifData[tag][1]] +
              StringValues.Components[exifData[tag][2]] +
              StringValues.Components[exifData[tag][3]];
            break;
        }
        tags[tag] = exifData[tag];
      }
    }
 
    if (tags.GPSInfoIFDPointer) {
      gpsData = readTags(file, tiffOffset, tiffOffset + tags.GPSInfoIFDPointer, GPSTags, bigEnd);
      for (tag in gpsData) {
        switch (tag) {
          case "GPSVersionID":
            gpsData[tag] = gpsData[tag][0] +
              "." + gpsData[tag][1] +
              "." + gpsData[tag][2] +
              "." + gpsData[tag][3];
            break;
        }
        tags[tag] = gpsData[tag];
      }
    }
 
    return tags;
  }
 
  EXIF.getData = function(img, callback) {
    if ((img instanceof Image || img instanceof HTMLImageElement) && !img.complete) return false;
    if (!imageHasData(img)) {
      getImageData(img, callback);
    } else {
      if (callback) {
        callback(img);
      }
    }
    return true;
  };
 
  EXIF.getTag = function(img, tag) {
    if (!imageHasData(img)) return;
    return img.exifdata[tag];
  };
 
  EXIF.getAllTags = function(img) {
    if (!imageHasData(img)) return {};
    var a,
      data = img.exifdata,
      tags = {};
    for (a in data) {
      if (data.hasOwnProperty(a)) {
        tags[a] = data[a];
      }
    }
    return tags;
  };
 
  EXIF.pretty = function(img) {
    if (!imageHasData(img)) return "";
    var a,
      data = img.exifdata,
      strPretty = "";
    for (a in data) {
      if (data.hasOwnProperty(a)) {
        if (typeof data[a] == "object") {
          if (data[a] instanceof Number) {
            strPretty += a + " : " + data[a] + " [" + data[a].numerator + "/" + data[a].denominator + "]\r\n";
          } else {
            strPretty += a + " : [" + data[a].length + " values]\r\n";
          }
        } else {
          strPretty += a + " : " + data[a] + "\r\n";
        }
      }
    }
    return strPretty;
  };
 
  EXIF.readFromBinaryFile = function(file) {
    return findEXIFinJPEG(file);
  };
 
  if (typeof define === 'function' && define.amd) {
    define('exif-js', [], function() {
      return EXIF;
    });
  }
}.call(this));
 
},{}]},{},[1]);