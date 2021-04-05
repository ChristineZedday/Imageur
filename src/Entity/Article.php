<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Nav;
use App\Entity\Metas;
use App\Entity\Footer;
use App\Entity\Section;


/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;


    /**
     * @ORM\OneToMany(targetEntity=Slider::class, mappedBy="article")
     */
    private $sliders;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="article")
     * @ORM\OrderBy({"rang" = "ASC"})
     */
    private $sections;

    /**
     * @ORM\ManyToOne(targetEntity=Rubrique::class, inversedBy="articles")
     */
    private $rubrique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description ='';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords ='';

    /**
     * @ORM\ManyToOne(targetEntity=Aside::class, inversedBy="articles")
     */
    private $aside;

   

    

    public function __construct($auteur)
    {
        $this->sliders = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->auteur = $auteur;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }


    /**
     * @return Collection|Slider[]
     */
    public function getSliders(): Collection
    {
        return $this->sliders;
    }

    public function addSlider(Slider $slider): self
    {
        if (!$this->sliders->contains($slider)) {
            $this->sliders[] = $slider;
            $slider->setArticle($this);
        }

        return $this;
    }

    public function removeSlider(Slider $slider): self
    {
        if ($this->sliders->removeElement($slider)) {
            // set the owning side to null (unless already changed)
            if ($slider->getArticle() === $this) {
                $slider->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setG($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getG() === $this) {
                $section->setG(null);
            }
        }

        return $this;
    }

    public function genereArticle($dir)
    {
        $path = $dir.'/'.$this->getNom().'.php';
        $articleFile = fopen($path, 'w');

        fwrite($articleFile, '<!DOCTYPE html><html lang="fr"><head><title>'.$this->getTitre().'</title>');
        fwrite($articleFile, '<meta name="author" content="'.$this->getAuteur().'" />');
        fwrite($articleFile, '<meta name="description" content="'.$this->getDescription().'"/>');
        fwrite($articleFile, '<meta name="keywords" content="'.$this->getKeywords().'"/>');
        if (!file_exists($dir.'/metas.php'))
        {
            $metas = new Metas();
            $metas->genereMetas($dir);}
        fwrite($articleFile, '<?php include(\'metas.php\'); ?>');
        fwrite($articleFile, '</head><body><div id = "conteneur">');
        if (file_exists($dir.'/nav.php'))
        { fwrite($articleFile, '<?php include(\'nav.php\'); ?>');}
        fwrite($articleFile, '<article class="contenu"><h1>'.$this->getTitre().'</h1>');
        foreach ($this->getSections() as $section)
       {
        if (!file_exists($dir.'/section_'.$section->getId().'.php'))
        {$section->genereSection($dir);}
       fwrite($articleFile, '<?php include(\'section_'.$section->getId().'.php\'); ?>');
      
      
       }
    
       if (!file_exists($dir.'/footer.php'))
        {
            $footer = new Footer();
            $footer->genereFooter($dir,'');}
        fwrite($articleFile, '</article></div>');
        if ($this->GetAside())
        {
            fwrite($articleFile, '<div class=element id="acote">'); 
            if (file_exists($dir.'/aside_'.$this->getAside()->getNom().'.php'))
            {
                fwrite($articleFile, '<?php include(\'aside_'.$this->getAside()->getNom().'.php\'); ?>');
            }
            fwrite($articleFile, '</div>');   
        }
        fwrite($articleFile, '   <script type="text/javascript" src="../ressources/js/main.js">  </script></body></html>');
        
    }

    public function getRubrique(): ?Rubrique
    {
        return $this->rubrique;
    }

    public function setRubrique(?Rubrique $rubrique): self
    {
        $this->rubrique = $rubrique;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getAside(): ?Aside
    {
        return $this->aside;
    }

    public function setAside(?Aside $aside): self
    {
        $this->aside = $aside;

        return $this;
    }


}
