<?php

class NameNormalizer
{
    
    public function normalize($name)
    {
        $textContent = preg_replace( '/\s+/', ' ', $name );
        
        switch($textContent) {
            case 'T. W. Baldwin':
            case 'Thomas W. Baldwin':
                $textContent = 'Baldwin, T[homas] W.';
                break;
        
            case 'Alexander Dyce':
                $textContent = 'Dyce, Alexander';
                break;
        
            case 'Arthur Quiller-Couch':
                $textContent = 'Quiller-Couch, Arthur';
                break;
                //Charles and Mary Cowden Clarke are a mess
                //@TODO: file an issue. 718 vs 815 of bibliography
                
            case 'Brink, Bernhard ten':
                $textContent = 'ten Brink, Bernhard';
                break;
                
            case 'Charles J. Sisson':
                $textContent = 'Sisson, Charles, J.';
                break;
                //@TODO: filter Bevington et al. 1148
            case 'David Bevington et al.':
            case 'David Bevington':
                $textContent = 'Bevington, David';
                break;
            case 'Edmond Malone':
            case 'Malone, Edmond (1741–1812)':
                $textContent = 'Malone, Edmond';
                break;
        
            case 'Edward Capell':
                $textContent = 'Capell, Edward';
                break;
        
            case 'G. Blakemore Evans et al.':
                $textContent = 'Evans, G. Blakemore';
                break;
        
            case 'Gary Taylor':
                $textContent = 'Taylor, Gary';
                break;
        
            case 'George Steevens':
            case 'Steevens, George (1736–1800)';
            $textContent = 'Steevens, George';
            break;
        
            case 'Gerard Langbaine':
                $textContent = 'Langbaine, Gerard';
                break;
        
            case 'Harry Levin':
                $textContent = 'Levin, Harry';
                break;
        
            case 'Reed, Isaac (1742–1807)':
            case 'Isaac Reed':
                $textContent = 'Reed, Isaac';
                break;
        
            case 'J[ohn] C. Trewin':
                $textContent = 'Trewin, J[ohn] C.';
                break;
        
        
            case 'James O. Halliwell':
                $textContent = 'Halliwell[-Phillipps], J[ames] O.';
                break;
        
            case 'John Dover Wilson':
                $textContent = 'Wilson, [John] Dover';
                break;
        
            case 'John Nichols':
                $textContent = 'Nichols, John';
                break;
        
            case 'John Payne Collier':
                $textContent = 'Collier, J[ohn] Payne';
                break;
        
            case 'John Philip Kemble':
                $textContent = 'Kemble, John Philip';
                break;
        
            case 'Karl Wentersdorf':
                $textContent = 'Wentersdorf, Karl';
                break;
        
            case 'Lewis Theobald':
                $textContent = 'Theobald, Lewis';
                break;
        
            case 'M. R. Ridley':
                $textContent = 'Ridley, M[aurice] R.';
                break;
        
            case 'Mariko Ichikawa':
                $textContent = 'Ichikawa, Mariko';
                break;
        
            case 'Miriam Joseph, Sr.':
                $textContent = 'Joseph, Sr. Miriam';
                break;
        
            case 'Paul Werstine':
                $textContent = 'Werstine, Paul';
                break;
        
            case 'Peter Alexander':
                $textContent = 'Alexander, Peter';
                break;
        
            case 'Richard Grant White':
                $textContent = 'White, Richard G.';
                break;
        
            case 'Samuel Johnson':
                $textContent = 'Johnson, Samuel';
                break;
        
            case 'Stanley Wells':
                $textContent = 'Wells, Stanley';
                break;
        
            case 'Stephen Greenblatt et al.':
                $textContent = 'Greenblatt, Stephen';
                break;
        
            case 'Styan Thirlby':
                $textContent = 'Thirlby, Styan';
                break;
        
            case 'Thiselton Dyer':
                $textContent = 'Dyer, T[homas] F. Thiselton';
                break;
        
            case 'Thomas Keightley':
                $textContent = 'Keightley, Thomas';
                break;
        
                //since I removed dates above, also remove other dates regardless of ambiguity
        
            case 'Blackstone, William (1723–80)':
                $textContent = 'Blackstone, William';
                break;
        
            case 'Brooks, Harold F. (1907–90)':
                $textContent = 'Brooks, Harold F.';
                break;
        
            case 'Craig, W[illiam] J. (1843–1906)':
                $textContent = 'Craig, W[illiam] J.';
                break;
        
            case 'Douce, Francis (1757–1834)':
                $textContent= 'Douce, Francis';
                break;
        
            case 'Furnivall, Frederick J. (1825–1910)':
                $textContent= 'Furnivall, Frederick J.';
                break;
        
            case 'Henley, Samuel (1740–1815)':
                $textContent= 'Henley, Samuel';
                break;
            case 'Ingleby, C[lement] M. (1823–86)':
                $textContent= 'Ingleby, C[lement] M.';
                break;
        
            case 'Jervis, Swynfen (1797–1867)':
                $textContent= 'Jervis, Swynfen';
                break;
        
            case 'Mason, John Monck (1726–1809)':
                $textContent= 'Mason, John Monck';
                break;
        
        
            case 'McKerrow, R[onald] B. (1872–1940)':
                $textContent= 'McKerrow, R[onald] B.';
                break;
        
            case 'Nicholson, Brinsley (1824–92)':
                $textContent= 'Nicholson, Brinsley';
                break;
        
            case 'Perring, Philip. (1828–1920)':
                $textContent= 'Perring, Philip.';
                break;
        
        
            case 'Ritson, Joseph (1752–1803)':
                $textContent= 'Ritson, Joseph';
                break;
        
            case 'Spedding, James (1808–81)':
                $textContent= 'Spedding, James';
                break;
        
            case 'Staunton, Howard (1810–74)':
                $textContent= 'Staunton, Howard';
                break;
        
        
            case 'Seager, H[erbert] W. (1848–?)':
                $textContent= 'Seager, H[erbert] W.';
                break;
        
            case 'Tollet, George (1725–79)':
                $textContent= 'Tollet, George';
                break;
        
            case 'Tyrwhitt, Thomas (1730–86)':
                $textContent= 'Tyrwhitt, Thomas';
                break;
        
        
            case 'Warburton, William (1698–1779)':
                $textContent= 'Warburton, William';
                break;
        
            case 'Weston, Stephen (1747–1830)':
                $textContent= 'Weston, Stephen';
                break;
        
                /* names like "first last"                     */
        
            case 'Nicholas Rowe':
                $textContent = 'Rowe, Nicholas';
                break;
        
            case 'Alexander Pope':
                $textContent = 'Pope, Alexander';
                break;
        
            case 'Thomas Hanmer':
                $textContent = 'Hanmer, Thomas';
                break;
        
            case 'William Warburton':
                $textContent = 'Warburton, William';
                break;
        
            case 'Joseph Rann':
                $textContent = 'Rann, Joseph';
                break;
        
            case 'James Boswell':
                $textContent = 'Boswell, James';
                break;
        
            case 'Samuel W. Singer':
                $textContent = 'Singer, Samuel W.';
                break;
        
            case 'Charles Knight':
                $textContent = 'Knight, Charles';
                break;
        
            case 'Henry N. Hudson':
                $textContent = 'Hudson, Henry N.';
                break;
        
            case 'Howard Staunton':
                $textContent = 'Staunton, Howard';
                break;
        
            case 'Nicolaus Delius':
                $textContent = 'Delius, Nicolaus';
                break;
        
            case 'William George Clark':
                $textContent = 'Clark, William George';
                break;
        
            case 'John Glover':
                $textContent = 'Glover, John';
                break;
        
            case 'William Aldis Wright':
                $textContent = 'Wright, William Aldis';
                break;
        
            case 'Charles & Mary Cowden Clarke':
            case 'Cowden Clarke, Charles & Mary':
                $textContent = 'Clarke, Charles & Mary Cowden';
                break;
        
            case 'William J. Rolfe':
                $textContent = 'Rolfe, William J.';
                break;
        
            case 'Henry Irving':
                $textContent = 'Irving, Henry';
                break;
        
            case 'Frank Marshall':
                $textContent = 'Marshall, Frank';
                break;
        
            case 'William J. Craig':
                $textContent = 'Craig, William J.';
                break;
        
            case 'A. H. Bullen':
                $textContent = 'Bullen, A. H.';
                break;
        
            case 'William Allan Neilson':
                $textContent = 'Neilson, William Allan';
                break;
        
            case 'Henry Cuningham':
                $textContent = 'Cuningham, Henry';
                break;
        
            case 'George Lyman Kittredge':
                $textContent = 'Kittredge, George Lyman';
                break;
        
            case 'R. A. Foakes':
                $textContent = 'Foakes, R. A.';
                break;
        
            case 'Paul A. Jorgensen':
                $textContent = 'Jorgensen, Paul A.';
                break;
        
            case 'T. S. Dorsch':
                $textContent = 'Dorsh, T. S.';
                break;
        
            case 'Joseph Ashbury':
                $textContent = 'Ashbury, Joseph';
                break;
        
            case 'Francis Gentleman':
                $textContent = 'Gentleman, Francis';
                break;
        
            case 'William Woods':
                $textContent = 'Woods, William';
                break;
        
            case 'Thomas Hull':
                $textContent = 'Hull, Thomas';
                break;
        
            case '[Elizabeth] Inchbald':
                $textContent = 'Inchbald, Elizabeth';
                break;
        
            case 'Frederick Reynolds':
                $textContent = 'Reynolds, Frederick';
                break;
        
            case 'Henry & Thomas Placide':
                $textContent = 'Placide, Henry & Thomas';
                break;
        
            case 'George Daniel':
                $textContent = 'Daniel, George';
                break;
        
            case 'Samuel Phelps':
                $textContent = 'Phelps, Samuel';
                break;
        
            case 'George Ellis':
                $textContent = 'Ellis, George';
                break;
        
            case 'John Sleeper Clarke':
                $textContent = 'Clarke, John Sleeper';
                break;
        
            case 'W. [A. B.] Hertzberg':
                $textContent = 'Hertzberg, W. A. B.';
                break;
        
            case 'W. H. Crane':
                $textContent = 'Crane, W. H.';
                break;
        
            case 'Stuart Robson':
                $textContent = 'Robson, Stuart';
                break;
        
            case 'C. H. Herford':
                $textContent = 'Herford, C. H.';
                break;
        
            case 'Charlotte Porter':
                $textContent = 'Porter, Charlotte';
                break;
        
            case 'Helen A. Clarke':
                $textContent = 'Clarke, Helen A.';
                break;
        
            case 'Theodore Komisarjevsky':
                $textContent = 'Komisarjevsky, Theodore';
                break;
        
            case 'Harden Craig':
                $textContent = 'Craig, Harden';
                break;
        
            case 'Clifford Williams':
                $textContent = 'Williams, Clifford';
                break;
        
            case 'Louis B. Wright':
                $textContent = 'Wright, Louis B.';
                break;
        
            case 'Virginia LaMar':
                $textContent = 'LaMar, Virginia';
                break;
        
            case 'Irving Ribner':
                $textContent = 'Ribner, Irving';
                break;
        
            case 'Alfred Harbage':
                $textContent = 'Harbage, Alfred';
                break;
        
            case 'Sylvan Barnet':
                $textContent = 'Barnet, Sylvan';
                break;
        
            case 'Trevor Nunn':
                $textContent = 'Nunn, Trevor';
                break;
        
        
            case 'Kurt Tetzeli von Rosador':
                $textContent = 'Tetzeli von Rosador, Kurt';
                break;
        
            case 'Barbara Mowat':
                $textContent = 'Mowat, Barbara';
                break;
        
            case 'George Gascoigne':
                $textContent = 'Gascoigne, George';
                break;
        
            case 'John Lyly':
                $textContent = 'Lyly, John';
                break;
        
            case 'Christopher Marlowe':
                $textContent = 'Marlowe, Christopher';
                break;
        
            case 'Thomas Nashe':
                $textContent = 'Nashe, Thomas';
                break;
        
            case 'Publius Ovidius Naso':
                $textContent = 'Ovid';
                break;
        
        
            case 'George Peele':
                $textContent = 'Peele, George';
                break;
        
            case 'Titus Maccius Plautus':
                $textContent = 'Plautus';
                break;
        
        
            case 'Publius Terentius Afer':
                $textContent = 'Terence';
                break;
        
            case 'Publius Virgili Maronis':
                $textContent = 'Virgil';
                break;
                
            case 'Paul Yachnin':
                $textContent = 'Yachnin, Paul';
                break;
                
            case 'R. M. Pool':
                $textContent = 'Pool, R. M';
                break;
                
            case 'Rev. F[rank] P. Wilson ':
                $textContent = 'Wilson, Rev. F[rank] P.';
                break;
                
            case 'Annabelle Henkin Melzer':
                $textContent = 'Melzer, Annabelle Henkin';
                break;
                
            case 'Charlton Ogburn, Jr':
                $textContent = 'Ogburn, Charlton Jr.';
                break;
                
            case 'Clyde T. Warren':
                $textContent = 'Warren, Clyde T.';
                break;
                
            case 'David Thatcher':
                $textContent = 'Thatcher, David';
                break;
                
            case 'Edward G. Quinn':
                $textContent = 'Quinn, Edward G.';
                break;
                
                
            case 'John Jowett':
                $textContent = "Jowett, John";
                break;
                
            case 'Mary Cowden Clarke':
                $textContent = 'Clarke, Mary Cowden';
                break;
                
            case 'Michael Kobialka':
                $textContent = 'Kobialka, Michael';
                break;
                
                
        
        }
        return $textContent;        
        
    }
    
    
    
}